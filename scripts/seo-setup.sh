#!/bin/bash
# ============================================================
# EIKOU — SEO 技术地基一键部署（P0）
#
# 执行环境：宝塔面板终端，root 用户
# 用法：
#   bash /www/wwwroot/eikoujp.net/scripts/seo-setup.sh
#
# 需要翻译中英文 SEO 文案时（推荐，见步骤 6）：
#   DEEPL_API_KEY=你的key:fx bash scripts/seo-setup.sh
#
# 本脚本做什么：
#   1) 环境自检（wp-cli / 站点根 / 主题文件完整性）
#   2) 阻塞项检查：搜索引擎索引开关、冲突的 SEO 插件
#   3) 刷新重写规则（/sitemap-i18n.xml 依赖此步，不做会 404）
#   4) 修复文件属主
#   5) 清理页面缓存
#   6) 重新生成三语翻译（.mo），让新增 SEO 文案在 /zh/ /en/ 生效
#   7) 输出验证结果与后续手动步骤
#
# 本脚本不做什么：
#   - 不拉取 git（代码由你自己部署到位后再跑本脚本）
#   - 不改数据库内容、不动 wp-config.php、不动 uploads
# ============================================================
set -uo pipefail

WP_ROOT="${WP_ROOT:-/www/wwwroot/eikoujp.net}"
SITE_URL="${SITE_URL:-https://eikoujp.net}"
THEME_DIR="$WP_ROOT/wp-content/themes/wpbase-starter"

RED=$'\033[0;31m'; GRN=$'\033[0;32m'; YLW=$'\033[1;33m'; NC=$'\033[0m'
ok()   { echo "  ${GRN}[OK]${NC}   $*"; }
warn() { echo "  ${YLW}[WARN]${NC} $*"; }
fail() { echo "  ${RED}[FAIL]${NC} $*"; }

ERRORS=0
WARNS=0

echo "============================================================"
echo " EIKOU SEO 技术地基部署"
echo " 站点根目录: $WP_ROOT"
echo "============================================================"

# ─── 1. 环境自检 ───────────────────────────────────────────
echo ""
echo "[1/7] 环境自检"

if [ ! -d "$WP_ROOT" ]; then
    fail "站点根目录不存在: $WP_ROOT"
    echo "       请用 WP_ROOT=/实际/路径 bash $0 重新执行"
    exit 1
fi
ok "站点根目录存在"

cd "$WP_ROOT" || exit 1

# wp-cli：优先系统命令，回退到项目内的 wp-cli.phar
if command -v wp >/dev/null 2>&1; then
    WP="wp"
elif [ -f "$WP_ROOT/wp-cli.phar" ]; then
    WP="php $WP_ROOT/wp-cli.phar"
else
    fail "找不到 wp-cli（既无 wp 命令，也无 $WP_ROOT/wp-cli.phar）"
    exit 1
fi
WP="$WP --allow-root --path=$WP_ROOT"

if ! $WP core version >/dev/null 2>&1; then
    fail "wp-cli 无法连接 WordPress，请检查数据库配置与 wp-config.php"
    exit 1
fi
ok "wp-cli 可用（WordPress $($WP core version 2>/dev/null)）"

# 主题 SEO 文件完整性
MISSING=0
for f in inc/seo-core.php inc/seo-schema.php inc/seo-sitemap.php; do
    if [ ! -f "$THEME_DIR/$f" ]; then
        fail "主题缺少文件: $f"
        MISSING=1
    fi
done
if [ "$MISSING" -eq 1 ]; then
    echo ""
    echo "  ${RED}请先把新版主题代码部署到 $THEME_DIR 再执行本脚本${NC}"
    exit 1
fi
ok "SEO 模块文件齐全（seo-core / seo-schema / seo-sitemap）"

# PHP 语法检查，避免带病上线导致白屏
for f in functions.php inc/seo-core.php inc/seo-schema.php inc/seo-sitemap.php; do
    if ! php -l "$THEME_DIR/$f" >/dev/null 2>&1; then
        fail "PHP 语法错误: $f"
        php -l "$THEME_DIR/$f"
        exit 1
    fi
done
ok "PHP 语法检查通过"

# ─── 2. 阻塞项检查 ─────────────────────────────────────────
echo ""
echo "[2/7] 阻塞项检查"

# 搜索引擎索引开关 —— 这个关着的话后面一切都白做
BLOG_PUBLIC=$($WP option get blog_public 2>/dev/null || echo "?")
if [ "$BLOG_PUBLIC" = "1" ]; then
    ok "搜索引擎索引已开启 (blog_public=1)"
else
    fail "搜索引擎索引被关闭 (blog_public=$BLOG_PUBLIC)"
    echo "       这是致命项：开着的话所有 SEO 工作都无效"
    echo "       修复: $WP option update blog_public 1"
    echo "       或后台「設定 → 表示設定」取消勾选「検索エンジンがサイトをインデックスしないようにする」"
    ERRORS=$((ERRORS+1))
fi

# 冲突的 SEO 插件 —— 会输出第二份 canonical/OG，导致 Google 忽略整组
CONFLICTS=$($WP plugin list --status=active --field=name 2>/dev/null \
    | grep -Ei '^(wordpress-seo|seo-by-rank-math|all-in-one-seo-pack|autodescription|slim-seo|squirrly-seo)$' || true)
if [ -n "$CONFLICTS" ]; then
    fail "检测到冲突的 SEO 插件（会产生重复的 canonical / OG 标签）:"
    echo "$CONFLICTS" | sed 's/^/         - /'
    echo "       这些插件不认识 /zh/ /en/ 语言前缀，输出的 hreflang 和 canonical 会是错的"
    echo "       建议停用: $WP plugin deactivate <插件名>"
    ERRORS=$((ERRORS+1))
else
    ok "无冲突的 SEO 插件"
fi

# 物理 robots.txt 会盖过主题的动态输出
if [ -f "$WP_ROOT/robots.txt" ]; then
    warn "存在物理文件 robots.txt，它会覆盖主题动态生成的版本"
    echo "         当前内容:"
    sed 's/^/           /' "$WP_ROOT/robots.txt"
    echo "         如需用主题版本（含 sitemap 声明），请重命名: mv robots.txt robots.txt.bak"
    WARNS=$((WARNS+1))
else
    ok "无物理 robots.txt，将使用主题动态输出"
fi

# 固定链接必须是非默认结构，否则 sitemap 路由无法工作
PERMALINK=$($WP option get permalink_structure 2>/dev/null || echo "")
if [ -z "$PERMALINK" ]; then
    fail "固定链接为「默认」(?p=123)，SEO 与 sitemap 路由都无法正常工作"
    echo "       修复: $WP rewrite structure '/%postname%/'"
    ERRORS=$((ERRORS+1))
else
    ok "固定链接结构: $PERMALINK"
fi

if [ "$ERRORS" -gt 0 ]; then
    echo ""
    echo "${RED}发现 $ERRORS 个阻塞项，已停止。修复后重新执行本脚本。${NC}"
    exit 1
fi

# ─── 3. 刷新重写规则 ───────────────────────────────────────
echo ""
echo "[3/7] 刷新重写规则"
echo "      （/sitemap-i18n.xml 是新增路由，不刷新会返回 404）"
if $WP rewrite flush --hard >/dev/null 2>&1; then
    ok "重写规则已刷新"
else
    warn "wp rewrite flush 失败，请到后台「設定 → パーマリンク」点一次「変更を保存」"
    WARNS=$((WARNS+1))
fi

# ─── 4. 文件属主 ───────────────────────────────────────────
echo ""
echo "[4/7] 修复文件属主"
if chown -R www:www "$THEME_DIR" 2>/dev/null; then
    ok "属主已设为 www:www"
else
    warn "chown 失败（可能属主名不是 www），请按实际环境手动处理"
    WARNS=$((WARNS+1))
fi

# ─── 5. 清理缓存 ───────────────────────────────────────────
echo ""
echo "[5/7] 清理缓存"
$WP cache flush >/dev/null 2>&1 && ok "对象缓存已清理" || warn "对象缓存清理跳过"

CACHE_PLUGINS=$($WP plugin list --status=active --field=name 2>/dev/null \
    | grep -Ei 'cache|wp-rocket|litespeed' || true)
if [ -n "$CACHE_PLUGINS" ]; then
    warn "检测到缓存插件，请手动清空页面缓存后再验证:"
    echo "$CACHE_PLUGINS" | sed 's/^/         - /'
    WARNS=$((WARNS+1))
fi

# ─── 6. 重新生成三语翻译 ───────────────────────────────────
echo ""
echo "[6/7] 三语翻译（.mo）"
echo "      新增的 SEO 文案（首页标题/描述等）是日文字面量，"
echo "      不重新生成翻译的话 /zh/ 与 /en/ 会显示日文。"

if [ -n "${DEEPL_API_KEY:-}" ]; then
    if [ -f "$WP_ROOT/scripts/i18n-make-translations.sh" ]; then
        echo "      正在提取 → 翻译 → 编译 .mo ..."
        if DEEPL_API_KEY="$DEEPL_API_KEY" bash "$WP_ROOT/scripts/i18n-make-translations.sh"; then
            ok "三语翻译已更新"
        else
            fail "翻译生成失败，请单独排查后重跑 i18n-make-translations.sh"
            WARNS=$((WARNS+1))
        fi
    else
        warn "找不到 scripts/i18n-make-translations.sh，跳过"
        WARNS=$((WARNS+1))
    fi
else
    warn "未提供 DEEPL_API_KEY，跳过翻译生成"
    echo "         /zh/ 与 /en/ 的新增 SEO 文案会暂时显示日文。"
    echo "         补做: cd $WP_ROOT && DEEPL_API_KEY=你的key:fx bash scripts/i18n-make-translations.sh"
    WARNS=$((WARNS+1))
fi

# ─── 7. 验证 ───────────────────────────────────────────────
echo ""
echo "[7/7] 验证"
if [ -f "$WP_ROOT/scripts/seo-verify.sh" ]; then
    SITE_URL="$SITE_URL" bash "$WP_ROOT/scripts/seo-verify.sh"
else
    warn "找不到 scripts/seo-verify.sh，跳过自动验证"
    echo "         手动验证: curl -s $SITE_URL/ | grep -E 'canonical|alternate'"
fi

# ─── 收尾 ──────────────────────────────────────────────────
cat <<EOF

============================================================
 部署完成（警告 $WARNS 项）
============================================================

接下来需要在浏览器里手动做的事：

 1. Google Search Console
    - 用 DNS TXT 验证【域名属性】eikoujp.net（一次覆盖三语）
    - 另建 3 个 URL 前缀属性，用于分语言看数据：
        $SITE_URL/
        $SITE_URL/zh/
        $SITE_URL/en/
    - 提交站点地图: sitemap-i18n.xml
    - 1~2 周后查「国際ターゲティング」确认无 hreflang 错误

 2. 富媒体结果测试（三语各测一个 URL）
    https://search.google.com/test/rich-results

 3. Bing Webmaster Tools
    可直接从 GSC 导入，5 分钟

 4. 待补充的真实数据（提供后我再写进结构化数据）
    - 资本金（page-about.php 目前是占位符 XXXX万円）
    - 法人番号（13 桁）
    - Google Business Profile / SNS 链接（用于 sameAs 实体串联）

============================================================
EOF
