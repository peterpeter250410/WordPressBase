# 多语言（日本語 / 中文 / English）说明与运维手册

站点：`https://eikoujp.net/`　日文根目录、中文 `/zh/`、英文 `/en/`

## 架构（最终采用）

**主题 gettext（.mo）+ 内置轻量语言路由**，**不使用 Polylang**。

- 全站硬编码文字用 `__()/_e()` 包裹；服务数据数组由自定义提取器收录、输出点用 `__($var,'eikou')`。
- `functions.php` 顶部的轻量路由：
  - 识别 URL 前缀 `/zh` `/en`（存入 `$GLOBALS['eikou_lang']`），再剥离前缀让 WP 正常路由；
  - 挂 `locale` + `determine_locale` 过滤器并显式 `load_textdomain`，按语言加载 `eikou-zh_CN.mo` / `eikou-en_US.mo`；
  - `home_url` / `page_link` / `post_type_link` 加语言前缀，保持导航停留在当前语言；
  - 次级语言禁用 `redirect_canonical`（前缀已剥离，避免重定向死循环）。
- 译文：DeepL 机器翻译（源=日文），可人工校对。
- 优点：无需为每个页面建翻译副本；新增页面自动支持三语。

> 曾尝试 Polylang，但它按「每语言一份独立页面」路由，与本方案（同页面 + locale 切换）不匹配，故弃用并停用。

## 涉及脚本

| 脚本 | 作用 | 运行位置 |
|------|------|---------|
| `scripts/i18n-extract.php` | 扫描全站日文字面量 → `languages/eikou.pot` | 服务器（被下者调用）|
| `scripts/i18n-translate-po.php` | DeepL 翻译 .po（Header 认证）| 服务器（被下者调用）|
| `scripts/i18n-make-translations.sh` | 提取→翻译→编译 .mo 一键 | 服务器 |
| `scripts/server-deploy.sh` | 部署主题（已排除 languages 的 .po/.mo，不会误删）| 服务器 |

## 首次上线步骤（已完成，留档备查）

```bash
# 0. 更新 scripts（server-deploy 只同步主题，不含 scripts）
cd /tmp && rm -rf eikou-scripts
git clone --depth 1 https://github.com/peterpeter250410/WordPressBase.git eikou-scripts
cp -a /tmp/eikou-scripts/scripts/. /www/wwwroot/eikoujp.net/scripts/
rm -rf /tmp/eikou-scripts

# 1. 部署主题
bash /www/wwwroot/eikoujp.net/scripts/server-deploy.sh

# 2. 生成翻译（提取+DeepL+编译.mo，写入线上主题 languages/）
cd /www/wwwroot/eikoujp.net
DEEPL_API_KEY=你的key:fx bash scripts/i18n-make-translations.sh
```

## 日常运维

### 改了模板文字（新增/修改可翻译串）后
```bash
# 部署代码
bash /www/wwwroot/eikoujp.net/scripts/server-deploy.sh
# 重新生成翻译（会跳过已译、只翻新增，重编译 .mo）
cd /www/wwwroot/eikoujp.net
DEEPL_API_KEY=你的key:fx bash scripts/i18n-make-translations.sh
```

### 人工校对某条译文
编辑 `wp-content/themes/wpbase-starter/languages/eikou-zh_CN.po`（或 en_US）里的 `msgstr`，然后：
```bash
cd /www/wwwroot/eikoujp.net
wp i18n make-mo wp-content/themes/wpbase-starter/languages --allow-root
```
（只重编译，不重新机器翻译）

## 重要注意

- **翻译文件（.po/.mo）只在服务器上**，不在 git。`server-deploy.sh` 已用 `--exclude` 保护，不会被部署删除。如需版本化，可手动把 `languages/eikou-*.po` 提交到仓库。
- **动态录入内容不走 .mo**：后台录入的作品/视频/合作等文章标题正文属于用户数据，不会被模板翻译。当前这些区块多为硬编码回退文案（已翻译）。若录入真实文章且需多语言，需另行处理（如逐语言录入或引入内容翻译方案）。
- **公司信息（名称/地址/电话/邮箱）**跨语言不变，故意保留原值。

## 验证

```bash
echo "日:"; curl -s https://eikoujp.net/    | grep -o 'section-title">[^<]*' | head -1
echo "中:"; curl -s https://eikoujp.net/zh/ | grep -o 'section-title">[^<]*' | head -1
echo "英:"; curl -s https://eikoujp.net/en/ | grep -o 'section-title">[^<]*' | head -1
```
期望：主要事業 / 主要业务 / Core Business(es)
