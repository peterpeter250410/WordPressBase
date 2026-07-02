# 多语言（日本語 / 中文 / English）上线执行手册

适用站点：`https://eikoujp.net/`
架构：Polylang（免费）+ 主题 gettext 国际化 + DeepL 机器翻译
URL：日文根目录、中文 `/zh/`、英文 `/en/`

> 全程在**服务器**上执行（SSH 登录 root）。命令可整段复制粘贴。

---

## 阶段 A — 执行前准备（务必先做）

### A1. 备份数据库（用于回退）
```bash
cd /www/wwwroot/eikoujp.net
wp db export backup-before-i18n.sql --allow-root
```
成功会生成 `backup-before-i18n.sql`。

### A2.（可选）备份当前主题
```bash
cp -a /www/wwwroot/eikoujp.net/wp-content/themes/wpbase-starter \
      /www/wwwroot/eikoujp.net/wp-content/themes/wpbase-starter.bak.$(date +%s)
```

---

## 阶段 B — 部署代码

### B0. 更新服务器上的 scripts 目录
> `server-deploy.sh` 只同步主题目录，不含 scripts；i18n 脚本是新增的，需先拉下来。
```bash
cd /tmp && rm -rf eikou-scripts
git clone --depth 1 https://github.com/peterpeter250410/WordPressBase.git eikou-scripts
cp -a /tmp/eikou-scripts/scripts/. /www/wwwroot/eikoujp.net/scripts/
rm -rf /tmp/eikou-scripts
```

### B1. 部署最新主题
```bash
bash /www/wwwroot/eikoujp.net/scripts/server-deploy.sh
```
**预期**：最后输出 `[OK] 主题部署完成。`

---

## 阶段 C — 安装并配置多语言

### C1. 安装 Polylang + 建三语 + 子目录 URL
```bash
bash /www/wwwroot/eikoujp.net/scripts/i18n-setup.sh
```
**预期**：末尾列出 3 个语言（日本語 / 中文 / English）。

### C2. 提取文案 + DeepL 机器翻译 + 编译 .mo
```bash
cd /www/wwwroot/eikoujp.net
DEEPL_API_KEY=4b045899-5568-4fab-bb89-d2ce8aaf7b82:fx bash scripts/i18n-make-translations.sh
```
**预期**：依次打印
```
[1/4] 提取源字符串 ... 提取完成: N 条
[2/4] 由 pot 生成/合并 ...
[3/4] DeepL 机器翻译 ... [ZH] 待翻译 N 条 ... [EN-US] ...
[4/4] 编译 .mo ...
[OK] 已生成 eikou-zh_CN.mo / eikou-en_US.mo
```

> **若步骤 [4/4] 报缺命令**，任选其一后重跑 C2：
> ```bash
> wp package install wp-cli/i18n-command --allow-root
> # 或
> yum install -y gettext        # Ubuntu/Debian: apt-get install -y gettext
> ```

---

## 阶段 D — 验证

### D1. 命令行快速验证（服务器）
```bash
UA_PC="Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
UA_M="Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148"
echo "日文首页:"; curl -s "https://eikoujp.net/"    | grep -o "主要事業\|Core Business" | head -1
echo "中文首页:"; curl -s "https://eikoujp.net/zh/" | grep -o "主要\|主营\|事業\|事业" | head -1
echo "英文首页:"; curl -s "https://eikoujp.net/en/" | grep -o "Core Business\|Main Business\|Business" | head -1
```

### D2. 浏览器验证
| 地址 | 期望 |
|------|------|
| `https://eikoujp.net/` | 日文 |
| `https://eikoujp.net/zh/` | 中文 |
| `https://eikoujp.net/en/` | 英文 |

逐项检查：
1. PC 顶部语言下拉、H5 菜单内语言 → 点击正确跳转、当前语言高亮
2. 首页、服务、服务子页、作品、视频、关于、合作、联系 各页文案随语言变化
3. PC 与手机（或 DevTools 手机模式）都测
4. CF7 联系表单、H5 浮窗在三语下正常

---

## 阶段 E — 收尾（可选）

### E1. 人工校对译文
机器翻译后如需修正个别措辞：
```bash
# 编辑 languages/eikou-zh_CN.po / eikou-en_US.po 里的 msgstr
# 改完重跑（会跳过已译条目，只重新编译 .mo）
cd /www/wwwroot/eikoujp.net
DEEPL_API_KEY=xxx:fx bash scripts/i18n-make-translations.sh
```

### E2. 翻译已录入的 CPT 内容
后台 Polylang 里为已有的作品/视频/合作等文章补充中英翻译（数量少）。

### E3. 菜单多语言
若使用了 WordPress 后台菜单（非回退菜单），在 Polylang 里为每个语言分配对应菜单。

---

## 回退方案（万一出问题）

```bash
# 1. 停用 Polylang
wp plugin deactivate polylang --allow-root

# 2. 恢复数据库
cd /www/wwwroot/eikoujp.net
wp db import backup-before-i18n.sql --allow-root

# 3.（如替换过主题）恢复主题备份
# rm -rf wp-content/themes/wpbase-starter
# mv wp-content/themes/wpbase-starter.bak.XXturned /www/.../wpbase-starter
```

---

## 常见问题

| 现象 | 原因 / 处理 |
|------|------------|
| `/zh/` `/en/` 打不开(404) | Polylang URL 未生效 → 重跑 C1，再 `wp rewrite flush --allow-root` |
| 切换后文案仍是日文 | .mo 未生成/未部署 → 检查 `wp-content/themes/wpbase-starter/languages/eikou-zh_CN.mo` 是否存在；重跑 C2 |
| 首页显示 PC 版但手机访问 | 页面缓存问题（本次已关 WP Super Cache）；如又出现，清缓存 |
| DeepL 报 403/456 | Key 错误或额度用尽 → 检查 Key（免费版以 `:fx` 结尾），额度见 DeepL 后台 |
| 部分文字没翻译 | 该文案可能是新加的/未提取 → 重跑 C2（会重新提取+翻译） |
| 公司名/地址/电话没翻译 | 正常，这些跨语言不变，故意保留原值 |
