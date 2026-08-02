#!/bin/bash
# ============================================
# EIKOU — 服务器端部署脚本
# 从 GitHub 拉取最新代码，更新主题 + 运维脚本 + 文档
# （不碰 WordPress 核心 / wp-config.php / uploads / 插件）
#
# 用法（在【服务器】上执行）:
#   bash scripts/server-deploy.sh              # 默认分支 main
#   bash scripts/server-deploy.sh rg           # 指定分支
#   BRANCH=dev bash scripts/server-deploy.sh   # 环境变量指定
#
# 同步范围：
#   wp-content/themes/wpbase-starter/   主题（保留服务器上的 .po/.mo）
#   scripts/                            运维脚本
#   docs/                               文档
# ============================================
set -euo pipefail

REPO="${REPO:-https://github.com/peterpeter250410/WordPressBase.git}"
BRANCH="${1:-${BRANCH:-main}}"
SITE="${SITE:-/www/wwwroot/eikoujp.net}"
THEME_DIR="$SITE/wp-content/themes/wpbase-starter"
TMP="/tmp/eikou-deploy-$$"

cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

echo "=============================="
echo " 分支: $BRANCH"
echo " 站点: $SITE"
echo "=============================="

echo "[1/5] 克隆最新代码 (GitHub)..."
rm -rf "$TMP"
if ! git clone --depth 1 -b "$BRANCH" "$REPO" "$TMP" 2>&1 | tail -2; then
    echo "[ERROR] 克隆失败：分支 '$BRANCH' 不存在，或代码尚未推送，或网络不通"
    exit 1
fi

echo "[2/5] 同步主题目录..."
# 排除翻译文件：.po/.mo 只在服务器上维护、不在 git 里，--delete 会误删
if command -v rsync >/dev/null 2>&1; then
    rsync -a --delete --exclude '.git' \
        --exclude 'languages/eikou-*.mo' \
        --exclude 'languages/eikou-*.po' \
        --exclude 'languages/eikou.pot' \
        "$TMP/wp-content/themes/wpbase-starter/" "$THEME_DIR/"
else
    cp -a "$TMP/wp-content/themes/wpbase-starter/." "$THEME_DIR/"
fi

echo "[3/5] 同步 scripts/ 与 docs/..."
# 这两个目录不加 --delete：服务器上可能有本地脚本或密钥文件，只做覆盖更新
mkdir -p "$SITE/scripts" "$SITE/docs"
cp -a "$TMP/scripts/." "$SITE/scripts/"
cp -a "$TMP/docs/."    "$SITE/docs/"
chmod +x "$SITE/scripts/"*.sh 2>/dev/null || true

echo "[4/5] 修复属主权限 (www:www)..."
chown -R www:www "$THEME_DIR" 2>/dev/null || echo "  （chown 跳过，属主名可能不是 www）"

echo "[5/5] PHP 语法自检..."
# 带病上线会白屏，部署完立刻自检一遍
SYNTAX_OK=1
while IFS= read -r f; do
    if ! php -l "$f" >/dev/null 2>&1; then
        echo "  [FAIL] 语法错误: ${f#$THEME_DIR/}"
        php -l "$f" 2>&1 | head -2 | sed 's/^/         /'
        SYNTAX_OK=0
    fi
done < <(find "$THEME_DIR" -maxdepth 2 -name '*.php')

if [ "$SYNTAX_OK" -eq 0 ]; then
    echo ""
    echo "[ERROR] 主题存在 PHP 语法错误，站点可能白屏。请立即修复或回滚。"
    exit 1
fi
echo "  [OK] 主题 PHP 文件语法正常"

cat <<EOF

==============================
[OK] 部署完成（分支 $BRANCH）
==============================
已同步：主题 / scripts / docs

后续：
  wp cache flush --allow-root          # 有缓存插件请另行清页面缓存
  bash scripts/seo-verify.sh           # SEO 体检
EOF
