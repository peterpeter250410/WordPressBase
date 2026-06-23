#!/bin/bash
# ============================================
# EIKOU — 服务器端部署脚本
# 从 GitHub 拉取最新代码，只更新主题目录
# （不碰 WordPress 核心 / wp-config.php / uploads / 插件）
#
# 用法（在【服务器】上执行，WordPress 根目录）:
#   bash scripts/server-deploy.sh
# ============================================
set -e

REPO="https://github.com/peterpeter250410/WordPressBase.git"
SITE_THEME="/www/wwwroot/eikoujp.net/wp-content/themes/wpbase-starter"
TMP="/tmp/eikou-deploy-$$"

echo "[1/4] 克隆最新代码 (GitHub)..."
rm -rf "$TMP"
git clone --depth 1 "$REPO" "$TMP"

echo "[2/4] 同步主题目录（仅主题）..."
if command -v rsync >/dev/null 2>&1; then
    rsync -a --delete --exclude '.git' \
        "$TMP/wp-content/themes/wpbase-starter/" "$SITE_THEME/"
else
    cp -a "$TMP/wp-content/themes/wpbase-starter/." "$SITE_THEME/"
fi

echo "[3/4] 修复属主权限 (www:www)..."
chown -R www:www "$SITE_THEME" 2>/dev/null || true

echo "[4/4] 清理临时文件..."
rm -rf "$TMP"

echo "=============================="
echo "[OK] 主题部署完成。"
echo "刷新 https://eikoujp.net/ 验证（有缓存插件请先清缓存）。"
echo "=============================="
