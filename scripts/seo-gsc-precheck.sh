#!/bin/bash
# ============================================================
# EIKOU — Google Search Console 提交前核查（只读）
#
# 用法：
#   bash /www/wwwroot/eikoujp.net/scripts/seo-gsc-precheck.sh
#
# 提交 sitemap 之后再发现问题，GSC 那边要等好几天才会重新抓取。
# 本脚本把 Google 会检查的项目提前跑一遍，确认无误再去提交。
#
# 检查项：
#   1. sitemap 可达性与 XML 合法性
#   2. sitemap 内 URL 抽样实测（Google 会逐条抓，死链会被标记为错误）
#   3. robots.txt 是否放行、是否声明 sitemap
#   4. 关键页面有无 noindex
#   5. DNS TXT 验证记录（添加后用它确认已生效）
# ============================================================
set -uo pipefail

SITE_URL="${SITE_URL:-https://eikoujp.net}"
SITE_URL="${SITE_URL%/}"
DOMAIN="$(sed -E 's#^https?://##; s#/.*##' <<<"$SITE_URL")"
SITEMAP="$SITE_URL/sitemap-i18n.xml"
SAMPLE="${SAMPLE:-12}"          # 抽样条数

RED=$'\033[0;31m'; GRN=$'\033[0;32m'; YLW=$'\033[1;33m'; NC=$'\033[0m'
PASS=0; FAILED=0; WARNED=0
ok()   { echo "  ${GRN}[OK]${NC}   $*"; PASS=$((PASS+1)); }
bad()  { echo "  ${RED}[FAIL]${NC} $*"; FAILED=$((FAILED+1)); }
warn() { echo "  ${YLW}[WARN]${NC} $*"; WARNED=$((WARNED+1)); }

echo "============================================================"
echo " GSC 提交前核查 — $SITE_URL"
echo "============================================================"

# ─── 1. sitemap ────────────────────────────────────────────
echo ""
echo "── 1. sitemap ──"

CODE=$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 30 "$SITEMAP" 2>/dev/null)
if [ "$CODE" != "200" ]; then
    bad "sitemap 返回 HTTP $CODE — 先修好再提交"
    echo "       wp rewrite flush --hard --allow-root"
    exit 1
fi
ok "sitemap 可访问（HTTP 200）"

SM=$(curl -sS -L --max-time 30 "$SITEMAP" 2>/dev/null)

CT=$(curl -sSI -L --max-time 20 "$SITEMAP" 2>/dev/null | grep -i '^content-type:' | tr -d '\r')
if grep -qi 'xml' <<<"$CT"; then
    ok "Content-Type 正确（${CT#*: }）"
else
    bad "Content-Type 不是 XML：${CT:-未返回} — GSC 会拒绝解析"
fi

# XML 合法性（有 xmllint 就用，没有就退回粗检）
if command -v xmllint >/dev/null 2>&1; then
    if xmllint --noout - <<<"$SM" 2>/dev/null; then
        ok "XML 格式合法"
    else
        bad "XML 格式错误 — GSC 会报「无法读取此站点地图」"
        xmllint --noout - <<<"$SM" 2>&1 | head -3 | sed 's/^/         /'
    fi
else
    if grep -q '</urlset>' <<<"$SM"; then
        ok "XML 结构完整（未装 xmllint，仅粗检）"
    else
        bad "XML 结构不完整，缺少 </urlset>"
    fi
fi

LOCS=$(grep -c '<loc>' <<<"$SM" || echo 0)
ZH=$(grep -c '<loc>[^<]*/zh/' <<<"$SM" || echo 0)
EN=$(grep -c '<loc>[^<]*/en/' <<<"$SM" || echo 0)
JA=$((LOCS - ZH - EN))
ok "URL 总数 $LOCS（日 $JA / 中 $ZH / 英 $EN）"

if [ "$LOCS" -gt 50000 ]; then
    bad "超过 50,000 条上限，需要拆分为 sitemap index"
fi
SIZE=$(printf '%s' "$SM" | wc -c)
if [ "$SIZE" -gt 52428800 ]; then
    bad "未压缩体积超过 50MB 上限"
else
    ok "体积 $((SIZE/1024)) KB（上限 50MB）"
fi

# ─── 2. sitemap 内 URL 抽样实测 ─────────────────────────────
echo ""
echo "── 2. sitemap 内 URL 抽样（$SAMPLE 条）──"
echo "   Google 会逐条抓取，死链或重定向会被标记为错误"

URLS=$(grep -oP '<loc>\K[^<]+' <<<"$SM")
# 三语各取若干，保证覆盖面
PICK=$( { grep -v '/zh/\|/en/' <<<"$URLS" | head -$((SAMPLE/3));
          grep '/zh/' <<<"$URLS" | head -$((SAMPLE/3));
          grep '/en/' <<<"$URLS" | head -$((SAMPLE/3)); } )

BADURL=0
while IFS= read -r u; do
    [ -z "$u" ] && continue
    # 不跟随重定向：sitemap 里就该是最终 URL
    c=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 "$u" 2>/dev/null)
    if [ "$c" = "200" ]; then
        continue
    elif [ "$c" = "301" ] || [ "$c" = "302" ]; then
        warn "$c 重定向: $u"
        echo "         sitemap 应直接列最终 URL，不要列会跳转的地址"
        BADURL=$((BADURL+1))
    else
        bad "HTTP $c: $u"
        BADURL=$((BADURL+1))
    fi
done <<<"$PICK"

if [ "$BADURL" -eq 0 ]; then
    ok "抽样 URL 全部返回 200"
fi

# ─── 3. robots.txt ─────────────────────────────────────────
echo ""
echo "── 3. robots.txt ──"

RB=$(curl -sS -L --max-time 20 "$SITE_URL/robots.txt" 2>/dev/null)
if [ -z "$RB" ]; then
    bad "robots.txt 无内容"
else
    if grep -qiE '^\s*Disallow:\s*/\s*$' <<<"$RB"; then
        bad "存在全站 Disallow: / —— 搜索引擎完全无法抓取"
        echo "       检查: wp option get blog_public --allow-root  （应为 1）"
    else
        ok "未发现全站 Disallow"
    fi
    if grep -qi 'sitemap-i18n' <<<"$RB"; then
        ok "已声明 sitemap-i18n.xml"
    else
        bad "未声明 sitemap-i18n.xml"
        echo "       站点根目录若有物理 robots.txt，会覆盖主题的动态输出"
    fi
fi

# ─── 4. noindex 检查 ───────────────────────────────────────
echo ""
echo "── 4. 关键页面 noindex 检查 ──"

for p in "/" "/zh/" "/en/" "/service-booth-design/" "/works/" "/contact/"; do
    H=$(curl -sS -L --max-time 20 "$SITE_URL$p" 2>/dev/null)
    HDR=$(curl -sSI -L --max-time 20 "$SITE_URL$p" 2>/dev/null | grep -i 'x-robots-tag' || true)
    if grep -qiP '<meta[^>]+name="robots"[^>]+noindex' <<<"$H" || grep -qi 'noindex' <<<"$HDR"; then
        bad "$p 带 noindex —— 不会被收录"
    else
        ok "$p 无 noindex"
    fi
done

# ─── 5. DNS TXT 验证记录 ───────────────────────────────────
echo ""
echo "── 5. DNS TXT 验证记录 ──"
echo "   在 GSC 拿到 google-site-verification 值、去域名商添加 TXT 记录后，用这步确认已生效"

DIG=""
if command -v dig >/dev/null 2>&1; then
    DIG=$(dig +short TXT "$DOMAIN" 2>/dev/null)
elif command -v host >/dev/null 2>&1; then
    DIG=$(host -t TXT "$DOMAIN" 2>/dev/null)
elif command -v nslookup >/dev/null 2>&1; then
    DIG=$(nslookup -type=TXT "$DOMAIN" 2>/dev/null)
fi

if [ -z "$DIG" ]; then
    warn "本机没有 dig / host / nslookup，无法查询 DNS"
    echo "         可在浏览器用 https://dnschecker.org 查 $DOMAIN 的 TXT 记录"
elif grep -qi 'google-site-verification' <<<"$DIG"; then
    ok "已检测到 google-site-verification TXT 记录"
    grep -oi 'google-site-verification=[A-Za-z0-9_-]*' <<<"$DIG" | sed 's/^/         /'
else
    warn "尚未检测到 google-site-verification TXT 记录"
    echo "         若刚添加，DNS 生效通常需要 10 分钟~2 小时，稍后重跑本脚本"
    echo "         当前 $DOMAIN 的 TXT 记录："
    if [ -n "$DIG" ]; then sed 's/^/           /' <<<"$DIG" | head -5; else echo "           （无）"; fi
fi

# ─── 汇总 ──────────────────────────────────────────────────
echo ""
echo "============================================================"
echo " 通过 $PASS / 警告 $WARNED / ${RED}失败 $FAILED${NC}"
echo "============================================================"
if [ "$FAILED" -eq 0 ]; then
    echo " ${GRN}可以提交了。${NC}按 docs/seo-gsc-guide.md 的步骤操作。"
else
    echo " ${RED}有 $FAILED 项未通过，修复后再提交${NC}"
    echo " 带着错误提交的话，GSC 要等数天才会重新抓取，排查周期会被拉长。"
fi
echo ""
