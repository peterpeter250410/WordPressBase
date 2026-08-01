#!/bin/bash
# ============================================================
# EIKOU — SEO 三语体检脚本（只读，不修改任何东西）
#
# 用法：
#   bash /www/wwwroot/eikoujp.net/scripts/seo-verify.sh
#   SITE_URL=https://eikoujp.net bash scripts/seo-verify.sh
#
# 检查项：
#   A. 三语页面的 canonical / hreflang / title / description / JSON-LD
#   B. hreflang 互指一致性（三语的 alternate 组必须完全相同）
#   C. PC 与手机版输出一致性（防 cloaking —— 本站有 UA 动态服务架构）
#   D. sitemap-i18n.xml 与 robots.txt
# ============================================================
set -uo pipefail

SITE_URL="${SITE_URL:-https://eikoujp.net}"
SITE_URL="${SITE_URL%/}"

PC_UA="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
MB_UA="Mozilla/5.0 (Linux; Android 13; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36"

RED=$'\033[0;31m'; GRN=$'\033[0;32m'; YLW=$'\033[1;33m'; NC=$'\033[0m'
PASS=0; FAILED=0; WARNED=0
ok()   { echo "  ${GRN}[OK]${NC}   $*"; PASS=$((PASS+1)); }
bad()  { echo "  ${RED}[FAIL]${NC} $*"; FAILED=$((FAILED+1)); }
warn() { echo "  ${YLW}[WARN]${NC} $*"; WARNED=$((WARNED+1)); }

fetch() { curl -sS -L --max-time 20 -A "$1" "$2" 2>/dev/null; }
tagval() { grep -oP "$2" <<<"$1" | head -1; }

echo "============================================================"
echo " EIKOU SEO 三语体检 — $SITE_URL"
echo "============================================================"

# ─── A. 三语基础标签 ───────────────────────────────────────
echo ""
echo "── A. 三语页面基础标签 ──"

declare -A ALT_SETS
# 注意：数组下标不能为空字符串，bash 会报 bad array subscript 并中断整个循环。
# 日文用 "ja" 作键，URL 路径另行推导。
for lang in ja zh en; do
    if [ "$lang" = "ja" ]; then URL="$SITE_URL/"; LABEL="日本語  /"; else URL="$SITE_URL/$lang/"; LABEL="$lang     /$lang/"; fi

    echo ""
    echo "  ▼ $LABEL"

    CODE=$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 20 -A "$PC_UA" "$URL" 2>/dev/null)
    if [ "$CODE" != "200" ]; then
        bad "HTTP $CODE — 页面无法访问，后续检查跳过"
        continue
    fi
    ok "HTTP 200"

    HTML=$(fetch "$PC_UA" "$URL")

    # html lang 属性（应随语种变化，且必须与该语言一致）
    RAW_HTML_TAG=$(grep -oP '<html[^>]*>' <<<"$HTML" | head -1)
    HTMLLANG=$(tagval "$HTML" '<html[^>]*lang="\K[^"]*')
    case "$lang" in
        ja) WANT='ja' ;;
        zh) WANT='zh' ;;
        en) WANT='en' ;;
    esac
    if [ -z "$HTMLLANG" ]; then
        warn "缺少 html lang 属性"
    elif [[ "$HTMLLANG" == ${WANT}* ]]; then
        ok "html lang=\"$HTMLLANG\""
    else
        bad "html lang=\"$HTMLLANG\" 与页面语言($lang)不符 —— 会误导搜索引擎判定页面语种"
        echo "         原始标签: $RAW_HTML_TAG"
        echo "         页面中所有 <html> 标签数: $(grep -c '<html' <<<"$HTML")"
    fi

    # 重复标签检测（多个 SEO 来源同时输出时 Google 会忽略整组）
    N_CANON=$(grep -c 'rel="canonical"' <<<"$HTML" || echo 0)
    N_DESC=$(grep -c 'name="description"' <<<"$HTML" || echo 0)
    N_OGT=$(grep -c 'property="og:title"' <<<"$HTML" || echo 0)
    [ "$N_CANON" -gt 1 ] && bad "canonical 重复 $N_CANON 条 —— 多半有 SEO 插件在同时输出"
    [ "$N_DESC"  -gt 1 ] && bad "meta description 重复 $N_DESC 条"
    [ "$N_OGT"   -gt 1 ] && bad "og:title 重复 $N_OGT 条"

    # canonical 必须指向自己
    CANON=$(tagval "$HTML" 'rel="canonical" href="\K[^"]*')
    if [ -z "$CANON" ]; then
        bad "缺少 canonical"
    elif [ "${CANON%/}" = "${URL%/}" ]; then
        ok "canonical 指向自身: $CANON"
    else
        bad "canonical 指向了别处: $CANON （应为 $URL）"
    fi

    # hreflang：应有 ja / zh-Hans / en / x-default 共 4 条
    ALTS=$(grep -oP 'rel="alternate" hreflang="[^"]*" href="[^"]*"' <<<"$HTML" || true)
    ALT_COUNT=$(grep -c . <<<"$ALTS" 2>/dev/null || echo 0)
    if [ "$ALT_COUNT" -eq 4 ]; then
        ok "hreflang 4 条（ja / zh-Hans / en / x-default）"
    elif [ "$ALT_COUNT" -eq 0 ]; then
        bad "完全没有 hreflang —— 三语会被判为重复内容"
    else
        warn "hreflang 数量异常: $ALT_COUNT 条（期望 4）"
    fi
    ALT_SETS["$lang"]=$(grep -oP 'hreflang="[^"]*" href="[^"]*"' <<<"$ALTS" | sort | tr '\n' '|')

    # title
    TITLE=$(tagval "$HTML" '<title>\K[^<]*')
    if [ -n "$TITLE" ]; then
        ok "title: ${TITLE:0:70}"
    else
        bad "缺少 title"
    fi

    # description
    DESC=$(tagval "$HTML" 'name="description" content="\K[^"]*')
    if [ -z "$DESC" ]; then
        bad "缺少 meta description"
    else
        DLEN=$(printf '%s' "$DESC" | wc -m)
        # 显示上限按语种区分：全角字符占位宽，日中约 90、英文约 155
        if [ "$lang" = "en" ]; then DMAX=160; else DMAX=95; fi
        if [ "$DLEN" -gt "$DMAX" ]; then
            warn "description 偏长（$DLEN 字符 / 上限 $DMAX）: ${DESC:0:50}..."
        elif [ "$DLEN" -lt 40 ]; then
            warn "description 偏短（$DLEN 字符），关键词空间没用满: $DESC"
        else
            ok "description（$DLEN 字符）: ${DESC:0:50}..."
        fi
    fi

    # OG
    if grep -q 'property="og:title"' <<<"$HTML"; then
        OGLOCALE=$(tagval "$HTML" 'property="og:locale" content="\K[^"]*')
        ok "OG 标签存在（og:locale=$OGLOCALE）"
    else
        bad "缺少 OG 标签"
    fi

    # JSON-LD
    LD=$(grep -c 'application/ld+json' <<<"$HTML" || echo 0)
    if [ "$LD" -ge 2 ]; then
        ok "JSON-LD $LD 段"
    elif [ "$LD" -eq 0 ]; then
        bad "没有 JSON-LD 结构化数据"
    else
        warn "JSON-LD 仅 $LD 段（首页期望 ≥2：Organization + WebSite）"
    fi
done

# ─── B. hreflang 互指一致性 ────────────────────────────────
echo ""
echo "── B. hreflang 互指一致性 ──"
echo "   （三语页面的 alternate 组必须完全相同，否则整组失效）"
SET_JA="${ALT_SETS["ja"]:-}"
SET_ZH="${ALT_SETS["zh"]:-}"
SET_EN="${ALT_SETS["en"]:-}"

if [ -z "$SET_JA" ]; then
    bad "日文页没有 hreflang，无法比对"
elif [ "$SET_JA" = "$SET_ZH" ] && [ "$SET_JA" = "$SET_EN" ]; then
    ok "三语 hreflang 组完全一致，互指正确"
else
    bad "三语 hreflang 组不一致 —— Google 会丢弃整组"
    echo "         ja: $SET_JA"
    echo "         zh: $SET_ZH"
    echo "         en: $SET_EN"
fi

# ─── C. PC / 手机一致性（防 cloaking）──────────────────────
echo ""
echo "── C. PC 与手机版输出一致性 ──"
echo "   （本站按 UA 切换 h5/ 模板，两版 SEO 标签必须一致，否则可能被判 cloaking）"

for path in "/" "/zh/" "/en/"; do
    P_HTML=$(fetch "$PC_UA" "$SITE_URL$path")
    M_HTML=$(fetch "$MB_UA" "$SITE_URL$path")

    P_T=$(tagval "$P_HTML" '<title>\K[^<]*');  M_T=$(tagval "$M_HTML" '<title>\K[^<]*')
    P_C=$(tagval "$P_HTML" 'rel="canonical" href="\K[^"]*'); M_C=$(tagval "$M_HTML" 'rel="canonical" href="\K[^"]*')
    P_D=$(tagval "$P_HTML" 'name="description" content="\K[^"]*'); M_D=$(tagval "$M_HTML" 'name="description" content="\K[^"]*')

    DIFF=""
    [ "$P_T" != "$M_T" ] && DIFF="$DIFF title"
    [ "$P_C" != "$M_C" ] && DIFF="$DIFF canonical"
    [ "$P_D" != "$M_D" ] && DIFF="$DIFF description"

    if [ -z "$DIFF" ]; then
        ok "$path PC 与手机版一致"
    else
        bad "$path PC 与手机版不一致:$DIFF"
        [ "$P_T" != "$M_T" ] && echo "         PC title: $P_T" && echo "         MB title: $M_T"
    fi
done

# Vary 头（动态服务必需）
VARY=$(curl -sSI -L --max-time 20 -A "$PC_UA" "$SITE_URL/" 2>/dev/null | grep -i '^vary:' || true)
if grep -qi 'user-agent' <<<"$VARY"; then
    ok "Vary: User-Agent 已设置（动态服务必需）"
else
    warn "未检测到 Vary: User-Agent（可能被 CDN/缓存层剥离）"
fi

# ─── D. sitemap / robots ───────────────────────────────────
echo ""
echo "── D. sitemap 与 robots ──"

SM_CODE=$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 30 "$SITE_URL/sitemap-i18n.xml" 2>/dev/null)
if [ "$SM_CODE" = "200" ]; then
    SM=$(curl -sS -L --max-time 30 "$SITE_URL/sitemap-i18n.xml" 2>/dev/null)
    LOCS=$(grep -c '<loc>' <<<"$SM" || echo 0)
    ZH=$(grep -c '<loc>[^<]*/zh/' <<<"$SM" || echo 0)
    EN=$(grep -c '<loc>[^<]*/en/' <<<"$SM" || echo 0)
    XH=$(grep -c 'xhtml:link' <<<"$SM" || echo 0)

    ok "sitemap-i18n.xml 可访问，共 $LOCS 条 URL"
    if [ "$ZH" -gt 0 ] && [ "$EN" -gt 0 ]; then
        ok "含中文 $ZH 条 / 英文 $EN 条"
    else
        bad "sitemap 缺少次级语言 URL（zh=$ZH en=$EN）"
    fi
    if [ "$XH" -gt 0 ]; then
        ok "含 xhtml:link 语言标注 $XH 处"
    else
        bad "sitemap 缺少 xhtml:link 语言标注"
    fi
else
    bad "sitemap-i18n.xml 返回 HTTP $SM_CODE"
    echo "         多半是重写规则没刷新，执行: wp rewrite flush --hard --allow-root"
fi

RB=$(curl -sS -L --max-time 20 "$SITE_URL/robots.txt" 2>/dev/null)
if grep -qi 'sitemap-i18n' <<<"$RB"; then
    ok "robots.txt 已声明 sitemap-i18n.xml"
else
    bad "robots.txt 未声明 sitemap-i18n.xml"
    echo "         若站点根目录有物理 robots.txt，它会覆盖主题输出"
fi
if grep -qi 'Disallow: /$' <<<"$RB"; then
    bad "robots.txt 存在全站 Disallow —— 搜索引擎索引开关可能被关闭"
fi

# ─── 汇总 ──────────────────────────────────────────────────
echo ""
echo "============================================================"
echo " 通过 $PASS 项 / 警告 $WARNED 项 / ${RED}失败 $FAILED 项${NC}"
echo "============================================================"
if [ "$FAILED" -eq 0 ]; then
    echo " ${GRN}技术地基验收通过。${NC}下一步去 Google Search Console 提交 sitemap。"
else
    echo " ${RED}有 $FAILED 项未通过，请按上面的提示修复后重跑。${NC}"
fi
echo ""
exit 0
