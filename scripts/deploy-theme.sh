#!/bin/bash
# ============================================
# EIKOU — 主题一键部署脚本（本地 → 生产服务器）
#
# 用法（在项目根目录的 Git Bash 中执行）:
#   bash scripts/deploy-theme.sh
#
# 会把本地主题目录同步到服务器。优先用 rsync（只传改动，快），
# 没有 rsync 时自动回退到 scp（全量上传）。
# 执行时会提示输入服务器 root 密码（已配置 SSH 密钥则免密）。
# ============================================
set -e

# ---------- 服务器配置（按需修改）----------
SERVER_USER="root"
SERVER_HOST="142.171.48.237"
SERVER_PORT="22"
REMOTE_THEME="/www/wwwroot/eikoujp.net/wp-content/themes/wpbase-starter"

# ---------- 本地主题路径（自动定位，无需修改）----------
PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
LOCAL_THEME="${PROJECT_ROOT}/wp-content/themes/wpbase-starter"

if [ ! -d "$LOCAL_THEME" ]; then
    echo "[ERROR] 本地主题目录不存在: $LOCAL_THEME"
    exit 1
fi

echo "=============================="
echo "  本地: $LOCAL_THEME"
echo "  远程: ${SERVER_USER}@${SERVER_HOST}:${REMOTE_THEME}"
echo "=============================="

if command -v rsync >/dev/null 2>&1; then
    echo "[deploy] 使用 rsync 增量同步..."
    rsync -avz \
        -e "ssh -p ${SERVER_PORT}" \
        --exclude '.git' \
        --exclude 'node_modules' \
        --exclude '*.map' \
        "${LOCAL_THEME}/" \
        "${SERVER_USER}@${SERVER_HOST}:${REMOTE_THEME}/"
else
    echo "[deploy] 未检测到 rsync，改用 scp 全量上传..."
    scp -P "${SERVER_PORT}" -r "${LOCAL_THEME}/." \
        "${SERVER_USER}@${SERVER_HOST}:${REMOTE_THEME}/"
fi

echo "=============================="
echo "[OK] 主题部署完成。"
echo "若装有缓存插件请清缓存，然后刷新 https://eikoujp.net/ 验证。"
echo "=============================="
