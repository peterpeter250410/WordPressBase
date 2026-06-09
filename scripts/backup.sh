#!/bin/bash
# WordPressBase 备份脚本
# 用法: bash backup.sh [备份目录]
# 示例: bash backup.sh /www/backup/wordpress
# 不带参数则备份到 /www/backup/wordpress

set -e

SITE_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
BACKUP_DIR="${1:-/www/backup/wordpress}"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="wpbase_${DATE}"

echo "=== WordPressBase 备份脚本 ==="
echo "站点目录: ${SITE_ROOT}"
echo "备份目录: ${BACKUP_DIR}"
echo ""

# 创建备份目录
mkdir -p "${BACKUP_DIR}"

# 1. 备份数据库
echo "[1/3] 备份数据库..."
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    DB_NAME=$(grep "DB_NAME" "${SITE_ROOT}/wp-config.php" | cut -d "'" -f 4)
    DB_USER=$(grep "DB_USER" "${SITE_ROOT}/wp-config.php" | cut -d "'" -f 4)
    DB_PASS=$(grep "DB_PASSWORD" "${SITE_ROOT}/wp-config.php" | cut -d "'" -f 4)
    DB_HOST=$(grep "DB_HOST" "${SITE_ROOT}/wp-config.php" | cut -d "'" -f 4)

    if [ -n "$DB_NAME" ] && [ "$DB_NAME" != "{{DB_NAME}}" ]; then
        mysqldump -h"${DB_HOST}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" | gzip > "${BACKUP_DIR}/${BACKUP_NAME}_db.sql.gz"
        echo "  [OK] 数据库已备份: ${BACKUP_NAME}_db.sql.gz"
    else
        echo "  [SKIP] 数据库配置未设置，跳过数据库备份"
    fi
else
    echo "  [SKIP] wp-config.php 不存在，跳过数据库备份"
fi

# 2. 备份 wp-content
echo "[2/3] 备份 wp-content..."
tar -czf "${BACKUP_DIR}/${BACKUP_NAME}_wp-content.tar.gz" \
    -C "${SITE_ROOT}" wp-content \
    --exclude="wp-content/cache" \
    --exclude="wp-content/upgrade"
echo "  [OK] wp-content 已备份: ${BACKUP_NAME}_wp-content.tar.gz"

# 3. 备份配置文件
echo "[3/3] 备份配置文件..."
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    cp "${SITE_ROOT}/wp-config.php" "${BACKUP_DIR}/${BACKUP_NAME}_wp-config.php"
    echo "  [OK] wp-config.php 已备份"
fi
if [ -f "${SITE_ROOT}/.htaccess" ]; then
    cp "${SITE_ROOT}/.htaccess" "${BACKUP_DIR}/${BACKUP_NAME}_htaccess"
    echo "  [OK] .htaccess 已备份"
fi

# 清理旧备份（保留最近 30 天）
echo ""
echo "清理 30 天前的备份..."
find "${BACKUP_DIR}" -name "wpbase_*" -mtime +30 -delete 2>/dev/null || true

echo ""
echo "=== 备份完成 ==="
echo "备份文件位于: ${BACKUP_DIR}"
ls -lh "${BACKUP_DIR}/${BACKUP_NAME}"* 2>/dev/null
