#!/bin/bash
# ============================================
# EIKOU — 多语言(Polylang)安装与配置
# 日本語(默认,根目录) / 中文(/zh/) / English(/en/)
#
# 在【服务器】WordPress 根目录执行:
#   bash scripts/i18n-setup.sh
# ============================================
set -e
WP="wp --allow-root"

echo "[1/4] 安装并启用 Polylang..."
$WP plugin is-installed polylang >/dev/null 2>&1 || $WP plugin install polylang
$WP plugin activate polylang

echo "[2/4] 创建语言 日本語 / 中文 / English..."
# wp pll lang create <name> <slug> <locale>
$WP pll lang create 日本語 ja ja_JP   2>/dev/null || echo "  (ja 已存在，跳过)"
$WP pll lang create 中文   zh zh_CN   2>/dev/null || echo "  (zh 已存在，跳过)"
$WP pll lang create English en en_US  2>/dev/null || echo "  (en 已存在，跳过)"

echo "[3/4] 配置：子目录URL + 隐藏默认语言前缀 + 默认语言=ja..."
# force_lang: 1=子目录(/zh/ /en/)  hide_default: 1=默认语言不带前缀(日文在根)
$WP option patch update polylang force_lang 1    2>/dev/null || true
$WP option patch update polylang hide_default 1  2>/dev/null || true
$WP option patch update polylang default_lang ja 2>/dev/null || true

echo "[4/4] 刷新固定链接..."
$WP rewrite flush

echo "=============================="
echo "[OK] 多语言已配置："
$WP pll lang list 2>/dev/null || true
echo "=============================="
echo "提示：已录入的页面/菜单需在 Polylang 后台补充中/英翻译（动态内容量少）。"
echo "模板文字的翻译由 scripts/i18n-make-translations.sh 生成的 .mo 提供。"
