#!/bin/bash
# WordPressBase 安全审计检查脚本
# 用法: bash security-check.sh

SITE_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
ISSUES=0
WARNINGS=0

echo "=== WordPressBase 安全审计检查 ==="
echo "时间: $(date)"
echo "目录: ${SITE_ROOT}"
echo "=================================="

# 1. wp-config.php 权限检查
echo ""
echo "[检查 1] wp-config.php 文件权限"
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    PERMS=$(stat -c %a "${SITE_ROOT}/wp-config.php" 2>/dev/null || stat -f %Lp "${SITE_ROOT}/wp-config.php")
    if [ "$PERMS" != "600" ] && [ "$PERMS" != "400" ]; then
        echo "  [FAIL] wp-config.php 权限为 ${PERMS}，建议设为 600"
        ISSUES=$((ISSUES + 1))
    else
        echo "  [PASS] 权限正确: ${PERMS}"
    fi
else
    echo "  [WARN] wp-config.php 不存在（尚未部署？）"
    WARNINGS=$((WARNINGS + 1))
fi

# 2. 检查默认表前缀
echo ""
echo "[检查 2] 数据表前缀"
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    PREFIX=$(grep "table_prefix" "${SITE_ROOT}/wp-config.php" | grep -o "'[^']*'" | head -1 | tr -d "'")
    if [ "$PREFIX" = "wp_" ]; then
        echo "  [FAIL] 使用了默认表前缀 wp_，建议修改为自定义前缀"
        ISSUES=$((ISSUES + 1))
    else
        echo "  [PASS] 表前缀: ${PREFIX}"
    fi
fi

# 3. 检查调试模式
echo ""
echo "[检查 3] 调试模式状态"
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    if grep -q "WP_DEBUG.*true" "${SITE_ROOT}/wp-config.php"; then
        echo "  [FAIL] WP_DEBUG 处于开启状态（生产环境应关闭）"
        ISSUES=$((ISSUES + 1))
    else
        echo "  [PASS] WP_DEBUG 已关闭"
    fi
fi

# 4. 检查 DISALLOW_FILE_EDIT
echo ""
echo "[检查 4] 后台文件编辑器"
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    if grep -q "DISALLOW_FILE_EDIT.*true" "${SITE_ROOT}/wp-config.php"; then
        echo "  [PASS] 后台文件编辑器已禁用"
    else
        echo "  [FAIL] 后台文件编辑器未禁用，建议设置 DISALLOW_FILE_EDIT 为 true"
        ISSUES=$((ISSUES + 1))
    fi
fi

# 5. 检查密钥是否已替换
echo ""
echo "[检查 5] 认证密钥配置"
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    if grep -q "{{GENERATE_NEW}}" "${SITE_ROOT}/wp-config.php"; then
        echo "  [FAIL] 认证密钥未替换，请从 https://api.wordpress.org/secret-key/1.1/salt/ 获取"
        ISSUES=$((ISSUES + 1))
    else
        echo "  [PASS] 认证密钥已配置"
    fi
fi

# 6. 检查敏感文件是否存在
echo ""
echo "[检查 6] 敏感文件暴露"
for FILE in readme.html license.txt wp-config-sample.php; do
    if [ -f "${SITE_ROOT}/${FILE}" ]; then
        echo "  [WARN] ${FILE} 存在于根目录，建议删除或通过 .htaccess 阻止访问"
        WARNINGS=$((WARNINGS + 1))
    else
        echo "  [PASS] ${FILE} 不存在"
    fi
done

# 7. 检查 uploads 目录是否有 PHP 文件
echo ""
echo "[检查 7] uploads 目录 PHP 文件"
if [ -d "${SITE_ROOT}/wp-content/uploads" ]; then
    PHP_COUNT=$(find "${SITE_ROOT}/wp-content/uploads" -name "*.php" 2>/dev/null | wc -l)
    if [ "$PHP_COUNT" -gt 0 ]; then
        echo "  [FAIL] uploads 目录发现 ${PHP_COUNT} 个 PHP 文件（可能是恶意文件）"
        find "${SITE_ROOT}/wp-content/uploads" -name "*.php" 2>/dev/null
        ISSUES=$((ISSUES + 1))
    else
        echo "  [PASS] uploads 目录无 PHP 文件"
    fi
else
    echo "  [PASS] uploads 目录不存在"
fi

# 8. 检查 .htaccess 安全配置
echo ""
echo "[检查 8] .htaccess 安全配置"
if [ -f "${SITE_ROOT}/.htaccess" ]; then
    if grep -q "Options -Indexes" "${SITE_ROOT}/.htaccess"; then
        echo "  [PASS] 目录浏览已禁用"
    else
        echo "  [FAIL] 未禁用目录浏览"
        ISSUES=$((ISSUES + 1))
    fi
else
    echo "  [WARN] .htaccess 不存在"
    WARNINGS=$((WARNINGS + 1))
fi

# 9. 检查 mu-plugins 是否存在
echo ""
echo "[检查 9] 安全加固插件"
if [ -f "${SITE_ROOT}/wp-content/mu-plugins/security-hardening.php" ]; then
    echo "  [PASS] security-hardening.php 已部署"
else
    echo "  [FAIL] security-hardening.php 未部署"
    ISSUES=$((ISSUES + 1))
fi

# 10. 检查 SSL 配置
echo ""
echo "[检查 10] SSL 配置"
if [ -f "${SITE_ROOT}/wp-config.php" ]; then
    if grep -q "FORCE_SSL_ADMIN.*true" "${SITE_ROOT}/wp-config.php"; then
        echo "  [PASS] 后台强制 SSL 已启用"
    else
        echo "  [WARN] 未启用后台强制 SSL"
        WARNINGS=$((WARNINGS + 1))
    fi
fi

# 汇总
echo ""
echo "=================================="
echo "检查完成"
echo "  严重问题: ${ISSUES}"
echo "  警告: ${WARNINGS}"
if [ "$ISSUES" -gt 0 ]; then
    echo "  状态: 存在安全问题，请尽快修复"
    exit 1
else
    echo "  状态: 安全检查通过"
    exit 0
fi
