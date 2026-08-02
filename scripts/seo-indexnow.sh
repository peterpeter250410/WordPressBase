#!/bin/bash
# ============================================================
# EIKOU — IndexNow 批量推送（Bing / Yandex / Naver 等）
#
# 用法：
#   bash /www/wwwroot/eikoujp.net/scripts/seo-indexnow.sh          # 推送 sitemap 里的全部 URL
#   bash scripts/seo-indexnow.sh https://eikoujp.net/zh/about/     # 只推指定 URL
#
# 说明：
#   IndexNow 是 Bing 主导的即时收录协议，Yandex、Seznam、Naver 也支持。
#   推送后通常几小时内抓取，比等爬虫自己发现快得多。
#
#   ⚠️ Google 不支持 IndexNow。Google 侧只能靠 Search Console 提交 sitemap
#      和「URL 检查 → 请求编入索引」，没有可脚本化的公开接口。
#      （Google 的 sitemap ping 接口 google.com/ping 已于 2023 年停用，别再用了。）
#
# 首次运行会自动生成密钥文件并放到站点根目录，之后可重复运行。
# ============================================================
set -uo pipefail

SITE_URL="${SITE_URL:-https://eikoujp.net}"
SITE_URL="${SITE_URL%/}"
WP_ROOT="${WP_ROOT:-/www/wwwroot/eikoujp.net}"
HOST="$(sed -E 's#^https?://##; s#/.*##' <<<"$SITE_URL")"

RED=$'\033[0;31m'; GRN=$'\033[0;32m'; YLW=$'\033[1;33m'; NC=$'\033[0m'
ok()   { echo "  ${GRN}[OK]${NC}   $*"; }
warn() { echo "  ${YLW}[WARN]${NC} $*"; }
bad()  { echo "  ${RED}[FAIL]${NC} $*"; }

echo "============================================================"
echo " IndexNow 推送 — $SITE_URL"
echo "============================================================"

# ─── 1. 密钥 ───────────────────────────────────────────────
echo ""
echo "[1/3] 密钥"

KEYFILE_LOCAL="$WP_ROOT/.indexnow-key"
if [ -f "$KEYFILE_LOCAL" ]; then
    KEY=$(tr -d '[:space:]' < "$KEYFILE_LOCAL")
    ok "复用已有密钥 ${KEY:0:8}…"
else
    # IndexNow 要求 8-128 位十六进制字符
    KEY=$(head -c 16 /dev/urandom | od -An -tx1 | tr -d ' \n')
    printf '%s' "$KEY" > "$KEYFILE_LOCAL"
    ok "已生成新密钥 ${KEY:0:8}…（保存在 $KEYFILE_LOCAL）"
fi

# 密钥验证文件必须放在站点根目录，内容就是密钥本身
PUBKEY="$WP_ROOT/$KEY.txt"
if [ ! -f "$PUBKEY" ]; then
    printf '%s' "$KEY" > "$PUBKEY"
    chown www:www "$PUBKEY" 2>/dev/null || true
    ok "已创建验证文件 $KEY.txt"
fi

# 确认验证文件对外可访问，否则推送会被拒
VC=$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 20 "$SITE_URL/$KEY.txt" 2>/dev/null)
if [ "$VC" != "200" ]; then
    bad "密钥验证文件不可访问：$SITE_URL/$KEY.txt 返回 HTTP $VC"
    echo "       IndexNow 会拒绝推送。检查站点根目录权限与伪静态规则。"
    exit 1
fi
ok "验证文件可访问：$SITE_URL/$KEY.txt"

# ─── 2. 收集 URL ───────────────────────────────────────────
echo ""
echo "[2/3] 收集 URL"

if [ $# -gt 0 ]; then
    URLS=$(printf '%s\n' "$@")
    ok "使用命令行指定的 $# 条 URL"
else
    SM=$(curl -sS -L --max-time 30 "$SITE_URL/sitemap-i18n.xml" 2>/dev/null)
    URLS=$(grep -oP '<loc>\K[^<]+' <<<"$SM")
    N=$(grep -c . <<<"$URLS" || echo 0)
    if [ "$N" -eq 0 ]; then
        bad "从 sitemap 取不到 URL"
        exit 1
    fi
    ok "从 sitemap 取得 $N 条 URL"
fi

# ─── 3. 推送 ───────────────────────────────────────────────
echo ""
echo "[3/3] 推送"

# 单次请求上限 10000 条，这里按 500 分批，便于定位问题
BATCH=500
TOTAL=0; SENT=0
mapfile -t ARR <<<"$URLS"
TOTAL=${#ARR[@]}

for ((i=0; i<TOTAL; i+=BATCH)); do
    CHUNK=("${ARR[@]:i:BATCH}")
    LIST=$(printf '"%s",' "${CHUNK[@]}"); LIST="[${LIST%,}]"

    PAYLOAD=$(cat <<EOF
{"host":"$HOST","key":"$KEY","keyLocation":"$SITE_URL/$KEY.txt","urlList":$LIST}
EOF
)
    RESP=$(curl -sS -o /dev/null -w '%{http_code}' --max-time 60 \
        -X POST 'https://api.indexnow.org/IndexNow' \
        -H 'Content-Type: application/json; charset=utf-8' \
        --data-binary "$PAYLOAD" 2>/dev/null)

    case "$RESP" in
        200|202) ok "第 $((i/BATCH+1)) 批 ${#CHUNK[@]} 条 → HTTP $RESP 已接受"; SENT=$((SENT+${#CHUNK[@]})) ;;
        400) bad "第 $((i/BATCH+1)) 批 → 400 请求格式错误" ;;
        403) bad "第 $((i/BATCH+1)) 批 → 403 密钥验证失败，检查 $SITE_URL/$KEY.txt" ;;
        422) bad "第 $((i/BATCH+1)) 批 → 422 URL 与 host 不匹配" ;;
        429) warn "第 $((i/BATCH+1)) 批 → 429 频率超限，稍后重试" ;;
        *)   bad "第 $((i/BATCH+1)) 批 → HTTP $RESP" ;;
    esac
done

echo ""
echo "============================================================"
echo " 已推送 $SENT / $TOTAL 条"
echo "============================================================"
cat <<EOF

说明：
 · HTTP 200/202 表示已接受，不代表立即收录，通常几小时内抓取。
 · 内容有更新时重跑本脚本即可，无需改动任何配置。
 · Google 不支持 IndexNow，Google 侧请用 Search Console。

建议：把本脚本加进内容发布流程，或挂个每周的定时任务：
   0 3 * * 1 bash $WP_ROOT/scripts/seo-indexnow.sh >/dev/null 2>&1
EOF
