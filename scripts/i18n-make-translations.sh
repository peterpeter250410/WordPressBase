#!/bin/bash
# ============================================
# EIKOU — 提取主题字符串 + DeepL机器翻译 + 编译 .mo
#
# 用法（本地或服务器，WordPress 根目录）:
#   DEEPL_API_KEY=你的key bash scripts/i18n-make-translations.sh
#
# 依赖: wp-cli(含 i18n 命令)、php(含 curl)、DeepL API Key
#   若缺 i18n 命令: wp package install wp-cli/i18n-command --allow-root
# ============================================
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
THEME_DIR="$PROJECT_ROOT/wp-content/themes/wpbase-starter"
LANG_DIR="$THEME_DIR/languages"
WP="wp --allow-root"

if [ -z "$DEEPL_API_KEY" ]; then
    echo "[ERROR] 请先设置 DEEPL_API_KEY，例如:"
    echo "  DEEPL_API_KEY=xxxxxxxx:fx bash scripts/i18n-make-translations.sh"
    exit 1
fi
export DEEPL_API_KEY

mkdir -p "$LANG_DIR"

echo "[1/4] 提取源字符串 -> eikou.pot（自定义提取器，含数组日文）..."
php "$SCRIPT_DIR/i18n-extract.php" "$THEME_DIR" "$LANG_DIR/eikou.pot"

echo "[2/4] 由 pot 生成/合并 zh_CN、en_US 的 .po ..."
for loc in zh_CN en_US; do
    PO="$LANG_DIR/eikou-$loc.po"
    if [ ! -f "$PO" ]; then
        cp "$LANG_DIR/eikou.pot" "$PO"
    elif command -v msgmerge >/dev/null 2>&1; then
        msgmerge -U --backup=none "$PO" "$LANG_DIR/eikou.pot" || true
    fi
done

echo "[3/4] DeepL 机器翻译（只翻译未翻译条目，可重复运行）..."
php "$SCRIPT_DIR/i18n-translate-po.php" "$LANG_DIR/eikou-zh_CN.po" ZH
php "$SCRIPT_DIR/i18n-translate-po.php" "$LANG_DIR/eikou-en_US.po" EN-US

echo "[4/4] 编译 .mo ..."
if $WP i18n make-mo "$LANG_DIR" 2>/dev/null; then
    echo "  (wp i18n make-mo 完成)"
elif command -v msgfmt >/dev/null 2>&1; then
    echo "  (改用 msgfmt 编译)"
    for po in "$LANG_DIR"/eikou-*.po; do
        [ -f "$po" ] && msgfmt "$po" -o "${po%.po}.mo"
    done
else
    echo "[WARN] 未找到 wp i18n 命令，也没有 msgfmt。请任选其一："
    echo "       wp package install wp-cli/i18n-command --allow-root"
    echo "       或安装 gettext： yum install -y gettext  /  apt-get install -y gettext"
    echo "       然后重跑本脚本。"
fi

echo "=============================="
echo "[OK] 已生成 eikou-zh_CN.mo / eikou-en_US.mo"
echo "如需人工校对：编辑 languages/eikou-*.po 后重跑本脚本(会跳过已翻译条目并重新编译)。"
echo "=============================="
