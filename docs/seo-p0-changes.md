# SEO 技术地基（P0）改动清单

> 本批次范围：**纯白帽技术 SEO**，三语（日/中/英）全部支持。
> 不含内容生产、外链、灰帽手段。战略见 [seo-strategy.md](seo-strategy.md)。

---

## 一、文件改动一览

| 文件 | 状态 | 说明 |
|---|---|---|
| `wp-content/themes/wpbase-starter/inc/seo-core.php` | **新增** | canonical / hreflang / title / description / OG / robots.txt / 后台 SEO 字段 |
| `wp-content/themes/wpbase-starter/inc/seo-schema.php` | **新增** | JSON-LD 结构化数据 |
| `wp-content/themes/wpbase-starter/inc/seo-sitemap.php` | **新增** | 多语言 sitemap `/sitemap-i18n.xml` |
| `wp-content/themes/wpbase-starter/functions.php` | **修改** | 引入上述 3 个模块；删除生产环境 debug 日志 |
| `scripts/seo-setup.sh` | **新增** | 一键部署脚本（宝塔 root 执行） |
| `scripts/seo-verify.sh` | **新增** | 三语体检脚本（只读） |

**`functions.php` 的改动只有一处**（原第 1168–1172 行）：

```diff
-add_action('init', function() {
-    if (defined('REST_REQUEST') && REST_REQUEST) {
-        error_log('REST API 请求进来啦！路径: ' . $_SERVER['REQUEST_URI']);
-    }
-});
+/* ─── SEO ───
+   canonical / hreflang / title / description / OG / robots.txt
+   JSON-LD 结构化数据 / 多语言 sitemap
+   均适配 /zh/ /en/ 语言前缀，三语共用同一套模板 */
+require_once get_template_directory() . '/inc/seo-core.php';
+require_once get_template_directory() . '/inc/seo-schema.php';
+require_once get_template_directory() . '/inc/seo-sitemap.php';
```

> 删掉的那段是留在生产环境的调试代码，每个 REST 请求都会写一行日志、持续撑大 `error_log`。与 SEO 无关但属于同批清理。
> **其余 1177 行 `functions.php` 代码一字未动**，多语言路由、CPT、Customizer 等全部保持原样。

---

## 二、解决了什么

### 1. hreflang（本批次最关键的一项）

三语页面互指 + `x-default`，每页输出 4 条：

```html
<link rel="alternate" hreflang="ja"      href="https://eikoujp.net/about/">
<link rel="alternate" hreflang="zh-Hans" href="https://eikoujp.net/zh/about/">
<link rel="alternate" hreflang="en"      href="https://eikoujp.net/en/about/">
<link rel="alternate" hreflang="x-default" href="https://eikoujp.net/about/">
```

**改动前：** Google 看到 `/`、`/zh/`、`/en/` 三个 URL 结构相同、图片相同，无法判断是语言版本关系，典型后果是判为重复内容、只收录其中一个。
**改动后：** 三语被正确识别为同一内容的语言集群，各自独立收录、各自对应语种的搜索结果。

### 2. canonical

`functions.php:63` 为避免重定向死循环，对次级语言整体关闭了 `redirect_canonical`（这个决定是对的，**本次没有改动它**），副作用是 `/zh/about`、`/zh/about/`、`/zh/about/?x=1` 全部返回 200。现在由 canonical 兜底，全部收敛到唯一规范 URL。

同时 `remove_action('wp_head', 'rel_canonical')` 摘掉了 WordPress 自带的 canonical —— 它输出的是**不带语言前缀**的 URL，会把 `/zh/` 页面的规范 URL 指到日文页，比没有还糟。

### 3. 多语言 sitemap

新增 `/sitemap-i18n.xml`，每个路径输出三条 `<url>`，每条都带完整 `xhtml:link` 语言标注：

```xml
<url>
  <loc>https://eikoujp.net/zh/about/</loc>
  <lastmod>2026-08-01T03:20:00+00:00</lastmod>
  <xhtml:link rel="alternate" hreflang="ja"      href="https://eikoujp.net/about/"/>
  <xhtml:link rel="alternate" hreflang="zh-Hans" href="https://eikoujp.net/zh/about/"/>
  <xhtml:link rel="alternate" hreflang="en"      href="https://eikoujp.net/en/about/"/>
  <xhtml:link rel="alternate" hreflang="x-default" href="https://eikoujp.net/about/"/>
  <priority>0.8</priority>
</url>
```

**改动前：** `wp-sitemap.xml` 只有日文 URL（`functions.php:78` 把 `wp-sitemap` 排除在语言前缀之外），`/zh/` `/en/` 没有任何收录入口。
**改动后：** 三语全部进 sitemap，且 sitemap 里的语言标注与页面上的 hreflang 互为佐证，收录速度会明显提升。

### 4. title / description

- 首页标题从「站点名」换成关键词位：`展示会ブース制作・イベント企画・商業空間デザイン｜荣光株式会社`
- **24 个服务页自动生成标题**：直接复用 `eikou_get_service_items()` 里已有的 `title` + `category_name`，无需逐页手工填写
- description 同样复用服务数据里现成的 `description` 字段
- 日文按**字符数**（`mb_strlen`）截断到 120 字符 —— 用 `substr` 会切碎多字节字符产生乱码
- 后台每个页面/案例新增「SEO 設定」框，可手写覆盖自动值

### 5. 结构化数据（JSON-LD）

| 类型 | 输出位置 |
|---|---|
| `LocalBusiness` | 全站 |
| `WebSite` | 全站（声明三语 `inLanguage`） |
| `BreadcrumbList` | 非首页 |
| `Service` | 24 个服务详情页 |
| `Article` | 案例详情页 |
| `VideoObject` | 视频详情页（自动把 `03:24` 转成 `PT3M24S`） |

**只填了真实可确认的数据**：公司名、地址、电话、邮箱、设立年份（2009）。资本金和法人番号**故意未输出** —— `page-about.php:107` 目前是占位符 `XXXX万円`，结构化数据填假值会导致 Google 取消该站的富媒体结果资格。提供真实值后我再补。

日文地址会被自动拆成 schema.org 要求的字段：

```
東京都葛飾区柴又1丁目43-6MAC柴又コート 102
  → addressRegion:   東京都
    addressLocality: 葛飾区
    streetAddress:   柴又1丁目43-6MAC柴又コート 102
    postalCode:      125-0052   （〒 符号已剥离）
```

### 6. OG / Twitter Card

含 `og:locale` 与 `og:locale:alternate`（三语交叉声明）。分享到 LINE / 微信 / X 时会正确显示标题、描述、缩略图。

> **建议补一张 `wp-content/themes/wpbase-starter/assets/images/og-default.jpg`（1200×630）。** 目前没有这个文件时会自动回退到 logo，但 logo 尺寸不适合做分享图。日本 B2B 场景 LINE 分享很常见，值得做一张。

### 7. robots.txt

自动输出并声明两个 sitemap。**注意：如果站点根目录存在物理 `robots.txt` 文件，它会覆盖这里的动态输出** —— 部署脚本会检测并提醒。

---

## 三、部署步骤

```bash
# 1. 把新代码放到位（你自己的 git 流程，或用现成的部署脚本）
bash /www/wwwroot/eikoujp.net/scripts/server-deploy.sh

# 2. 跑 SEO 部署脚本（宝塔终端，root）
#    带 DeepL key 可一并生成中英文翻译，强烈建议带上
cd /www/wwwroot/eikoujp.net
DEEPL_API_KEY=你的key:fx bash scripts/seo-setup.sh
```

`seo-setup.sh` 会依次做：

| 步骤 | 内容 | 失败会怎样 |
|---|---|---|
| 1 | 环境自检（wp-cli / 站点根 / 文件完整性 / PHP 语法） | 中止 |
| 2 | **阻塞项检查**：搜索引擎索引开关、冲突 SEO 插件、物理 robots.txt、固定链接结构 | 中止 |
| 3 | **刷新重写规则** | 警告 |
| 4 | 修复文件属主 `www:www` | 警告 |
| 5 | 清理缓存 | 警告 |
| 6 | **重新生成三语 `.mo`** | 警告 |
| 7 | 自动跑体检脚本 | — |

> **第 3 步不能跳过。** `/sitemap-i18n.xml` 是新增的重写规则，不刷新会直接 404。
>
> **第 6 步关系到「三语都要支持」。** 新增的 SEO 文案（首页标题、描述）是日文字面量，不重新生成 `.mo` 的话 `/zh/` 和 `/en/` 会显示日文标题。没带 `DEEPL_API_KEY` 时脚本会警告并给出补做命令。

### 随时可单独跑的体检

```bash
bash /www/wwwroot/eikoujp.net/scripts/seo-verify.sh
```

只读、不改任何东西，检查四组：

- **A** 三语页面的 canonical / hreflang / title / description / OG / JSON-LD
- **B** hreflang 互指一致性（三语的 alternate 组必须完全相同，否则整组失效）
- **C** PC 与手机版输出一致性 —— 本站按 UA 切 `h5/` 模板属于动态服务，两版 SEO 标签不一致会被判 cloaking
- **D** sitemap 与 robots.txt

---

## 四、已验证

三语 URL 构造与 schema 辅助函数做了 20 项断言测试，全部通过，包括容易踩的边界：

| 用例 | 期望 | 说明 |
|---|---|---|
| `/zh/about/` → `/about/` | ✓ | 正常剥离 |
| `/zheng/` → `/zheng/` | ✓ | **不误伤**（`zh` 是 `zheng` 的前缀） |
| `/entrance/` → `/entrance/` | ✓ | **不误伤**（`en` 是 `entrance` 的前缀） |
| `/zh` → `/` | ✓ | 无尾斜杠也能处理 |
| 三语 URL 拼接 | ✓ | 无双前缀 `/zh/zh/` |

4 个 PHP 文件、2 个 shell 脚本均通过语法检查。

> 说明：这些是纯函数级验证。**hreflang、sitemap、结构化数据的真实效果必须在线上环境跑 `seo-verify.sh` 才能确认**，因为它们依赖 WordPress 运行时（固定链接、页面数据、`.mo` 加载）。

---

## 五、部署后需要你手动做的事

**必做：**

1. **Google Search Console**
   - 用 DNS TXT 验证**域名属性** `eikoujp.net`（一次覆盖三语）
   - 另建 3 个 URL 前缀属性分语言看数据：`/`、`/zh/`、`/en/`
   - 提交站点地图 `sitemap-i18n.xml`
   - 1~2 周后查「国際ターゲティング」确认无 hreflang 错误

2. **富媒体结果测试**（三语各测一个 URL）
   https://search.google.com/test/rich-results

**建议：**

3. Bing Webmaster Tools（可从 GSC 直接导入，5 分钟）
4. 做一张 `assets/images/og-default.jpg`（1200×630）

**需要你提供的数据**（提供后我补进结构化数据）：

5. 资本金真实数值（`page-about.php:107` 现为占位符 `XXXX万円`）
6. 法人番号（13 桁）
7. Google Business Profile / SNS 链接 —— 用于 `sameAs` 实体串联

---

## 六、下一批（P1，待确认后开工）

- 24 个服务页按关键词表逐页定制 title/description（映射表已在 [seo-execution.md](seo-execution.md) §E12）
- 内链自动化（相关服务 / 相关案例 / 簇心回链）
- 图片 alt 全量补齐 + `service-woodwork` 的 Unsplash 外链图替换为自有图
- Core Web Vitals 优化
- 视觉面包屑组件化
