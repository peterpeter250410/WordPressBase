#!/bin/bash
# WordPressBase 部署脚本
# 用法: bash deploy.sh [wordpress版本号]
# 示例: bash deploy.sh 6.5.5
# 不带参数则下载最新版: bash deploy.sh

set -e

WP_VERSION="${1:-latest}"
SITE_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

echo "=== WordPressBase 部署脚本 ==="
echo "站点目录: ${SITE_ROOT}"
echo "WordPress 版本: ${WP_VERSION}"
echo ""

# 1. 下载 WordPress 核心
echo "[1/5] 下载 WordPress 核心..."
cd /tmp
if [ "$WP_VERSION" = "latest" ]; then
    curl -O https://wordpress.org/latest.tar.gz
    TAR_FILE="latest.tar.gz"
else
    curl -O "https://wordpress.org/wordpress-${WP_VERSION}.tar.gz"
    TAR_FILE="wordpress-${WP_VERSION}.tar.gz"
fi
tar -xzf "$TAR_FILE"

# 2. 同步核心文件（不覆盖 wp-content 和 wp-config.php）
echo "[2/5] 同步核心文件..."
# 删除 wp-content 和 wp-config-sample.php，避免覆盖项目自定义内容
rm -rf /tmp/wordpress/wp-content
rm -f /tmp/wordpress/wp-config-sample.php
cp -rf /tmp/wordpress/* /tmp/wordpress/.* "${SITE_ROOT}/" 2>/dev/null || cp -rf /tmp/wordpress/* "${SITE_ROOT}/"

# 3. 检查 wp-config.php 是否存在
echo "[3/5] 检查配置文件..."
if [ ! -f "${SITE_ROOT}/wp-config.php" ]; then
    echo "  [警告] wp-config.php 不存在！"
    echo "  请执行以下命令创建配置文件："
    echo "  cp ${SITE_ROOT}/config/wp-config-sample.php ${SITE_ROOT}/wp-config.php"
    echo "  然后编辑 wp-config.php 填入数据库信息和密钥"
else
    echo "  [OK] wp-config.php 已存在"
fi

# 4. 设置文件权限
echo "[4/5] 设置文件权限..."
find "${SITE_ROOT}" -type f -exec chmod 644 {} \;
find "${SITE_ROOT}" -type d -exec chmod 755 {} \;
chmod 600 "${SITE_ROOT}/wp-config.php" 2>/dev/null || true
chmod 644 "${SITE_ROOT}/.htaccess" 2>/dev/null || true

# 设置 wp-content 的所有者（宝塔面板通常使用 www 用户）
chown -R www:www "${SITE_ROOT}/wp-content/uploads" 2>/dev/null || true

# 5. 清理临时文件
echo "[5/5] 清理临时文件..."
rm -rf /tmp/wordpress /tmp/"$TAR_FILE"

echo ""
echo "=== 部署完成 ==="
echo ""
echo "后续步骤："
echo "  1. 确认 wp-config.php 已正确配置（数据库、密钥、表前缀）"
echo "  2. 复制 .htaccess: cp ${SITE_ROOT}/config/.htaccess-sample ${SITE_ROOT}/.htaccess"
echo "  3. 确认数据库已创建"
echo "  4. 访问站点完成 WordPress 安装向导"
echo "  5. 运行安全检查: bash ${SITE_ROOT}/scripts/security-check.sh"
