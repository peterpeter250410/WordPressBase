# WordPressBase

WordPress 企业官网基础版本，包含安全加固、部署脚本和运维工具。适用于企业官网/展示站的二次开发。

## 特性

- 安全加固的 wp-config 配置模板
- Must-Use 安全插件（版本隐藏、XML-RPC 禁用、登录限制、安全头等）
- .htaccess 安全与性能优化模板
- 自动化部署、备份、安全检查脚本
- 宝塔面板部署支持
- 兼容 PHP 7.4+

## 项目结构

```
WordPressBase/
├── config/                        # 配置模板
│   ├── wp-config-sample.php       # wp-config 安全配置模板
│   └── .htaccess-sample           # .htaccess 安全+性能模板
├── scripts/                       # 运维脚本
│   ├── deploy.sh                  # 部署脚本
│   ├── backup.sh                  # 备份脚本
│   └── security-check.sh          # 安全审计检查脚本
├── docs/                          # 文档
│   ├── deployment.md              # 宝塔面板部署指南
│   └── plugins.md                 # 推荐插件清单
└── wp-content/
    └── mu-plugins/                # Must-Use 安全加固插件
        └── security-hardening.php
```

> WordPress 核心文件不纳入版本控制，通过部署脚本下载。

## 快速开始

```bash
# 1. 克隆仓库到站点目录
git clone https://github.com/你的用户名/WordPressBase.git /www/wwwroot/yourdomain.com

# 2. 运行部署脚本（下载 WordPress 核心）
cd /www/wwwroot/yourdomain.com
bash scripts/deploy.sh

# 3. 配置
cp config/wp-config-sample.php wp-config.php
cp config/.htaccess-sample .htaccess
# 编辑 wp-config.php 填入数据库信息和密钥

# 4. 安全检查
bash scripts/security-check.sh
```

详细部署步骤见 [docs/deployment.md](docs/deployment.md)。

## 安全加固清单

mu-plugins/security-hardening.php 已包含：

- [x] WordPress 版本号隐藏
- [x] XML-RPC 完全禁用
- [x] REST API 用户枚举防护
- [x] ?author=N 枚举防护
- [x] 安全 HTTP 头（X-Frame-Options 等）
- [x] 登录尝试限制（5次/15分钟）
- [x] 移除不必要的 wp_head 输出
- [x] 禁用 WordPress Emoji

wp-config 模板已包含：

- [x] 非默认表前缀
- [x] 禁用后台文件编辑器
- [x] 强制 SSL 后台
- [x] 生产环境调试关闭
- [x] 内存限制和修订版本限制

## 二次开发

基于此项目创建新项目：

```bash
# Fork 或复制此仓库
git clone https://github.com/你的用户名/WordPressBase.git 新项目名
cd 新项目名
git remote set-url origin 新项目的git地址

# 在 wp-content/themes/ 下开发自定义主题
# 在 wp-content/mu-plugins/ 下添加项目特定的功能
```

## 许可证

WordPress 遵循 GPLv2 许可证。本项目中的自定义代码同样遵循 GPLv2。
