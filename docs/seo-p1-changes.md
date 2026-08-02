# SEO P1 改动清单（页面级优化）

> 前置：[seo-p0-changes.md](seo-p0-changes.md)（技术地基，已线上验收 33/33）
> 本批范围：**纯白帽**，三语全支持。

---

## 一、文件改动

| 文件 | 状态 | 说明 |
|---|---|---|
| `inc/seo-keywords.php` | **新增** | 25 个页面 × 3 语种 = 150 条 title/description |
| `inc/seo-internal-link.php` | **新增** | 内链权重雕刻（主题簇 hub-spoke） |
| `inc/seo-core.php` | 修改 | 接入关键词表；新增 preconnect 资源提示 |
| `functions.php` | 修改 | 引入 2 个新模块 |
| `page-service-item.php` | 修改 | 插入「関連サービス」区块（1 行） |
| `h5/page-service-item.php` | 修改 | 同上（手机版） |

---

## 二、核心设计决定：SEO 文案不走 `.mo`

**这是本批最重要的一个判断。**

SEO 文案按语种**直接写在 PHP 映射表里**，不经过 gettext 翻译。理由：

| 语种 | 真实搜索词 |
|---|---|
| 日文 | `展示会 ブース 制作` |
| 中文 | `日本展会搭建`、`东京展台设计` |
| 英文 | `exhibition booth design Japan` |

**这三者互相不是对方的译文。** 走 `.mo` 只能得到日文的直译，拿不到中英文用户真正在搜的词——等于把三语站最值钱的部分浪费掉。

附带三个好处：

1. 不依赖 DeepL key、不用编辑 `.po`、不用跑 `make-mo`，**部署即生效**
2. 首页三语标题立刻各不相同（之前三语都是同一段日文）
3. `docs/seo-i18n-strings.md` 里那 3 条手工译文**不再需要**——已被本表覆盖

> 正文内容仍然走 `.mo`，不变。只有 SEO 元数据走这张表。

---

## 三、关键词覆盖

25 个页面（首页 + 24 个服务页）× 3 语种 × (title + description) = **150 条文案**。

示例（`service-booth-design`）：

| 语种 | title | 目标词 |
|---|---|---|
| ja | 展示会ブース制作・デザイン会社｜設計から施工まで | 展示会 ブース 制作 / デザイン 会社 |
| zh | 日本展会展台设计搭建｜东京展台制作公司 | 日本展会搭建 / 东京展台设计 |
| en | Exhibition Booth Design & Construction in Japan | exhibition booth design Japan |

### 长度已逐条校验

搜索结果显示上限按语种不同，超出会被 Google 截断：

| 语种 | title 上限 | description 上限 |
|---|---|---|
| 日文 / 中文 | 32 全角 | 90 全角 |
| 英文 | 60 | 155 |

150 条全部实测通过，**无超限、无偏短**（偏短＝没用满关键词空间，同样是浪费）。

品牌后缀按语种自动追加：日/中 `｜荣光株式会社`，英 `| EIKOU Co., Ltd.`。

---

## 四、内链权重雕刻

把 6 个服务类目做成封闭的主题簇，权重在簇内循环、向簇心汇聚。

每个服务详情页底部自动生成：

- **同簇 3 个兄弟服务**（精确匹配锚文本）
- **簇心回链**（既有的「一覧に戻る」按钮）
- **联系页 CTA**（泛锚文本）

### 实测覆盖

24 个服务**全部拿到 3 条内链**：

| 簇 | 成员数 | 同簇链接数 |
|---|---|---|
| 展示会・イベント | 7 | 3 |
| ブランドイベント | 5 | 3 |
| デジタル / AI / ブランディング / メディア | 各 3 | 2 + 跨簇补 1 |

3 成员的小簇只有 2 个兄弟，补齐逻辑从其他簇取 1 个——**避免小类目页面内链过少**，这是设计好的行为。

### 两个刻意的设计

- **固定排序**（按服务编号）。内链结构频繁变动会削弱权重传递，所以不用随机或按时间排序。
- **锚文本控制**。全站精确匹配锚文本占比压在 30% 以内，CTA 用泛锚文本稀释。100% 精确匹配是过度优化信号，反而有害。

区块复用现有 CSS 类（`service-points-grid` / `h5-points-list`），**不需要新增样式**。UI 文案同样按语种直接定义，三语即时生效。

---

## 五、发现的问题：全站 89 处图片挂在 Unsplash

排查图片时发现的，**范围比预期大得多**：

```
front-page.php      14 处      page-works.php     10 处
h5/front-page.php   12 处      functions.php      15 处（服务 hero 图）
… 合计 89 处
```

24 个服务里有 **10 个**的主图指向 Unsplash：
`service-woodwork` / `web-design` / `web-marketing` / `app-dev` / `ai-chatbot` / `ai-modeling` / `automation` / `package-design` / `print-design` / `signage`

### 为什么这是个问题

**① 性能** —— 首屏 LCP 图片走第三方域名，要多付 DNS + TCP + TLS 三次往返，直接拖慢 LCP 指标。

**② 内容原创性（更重要）** —— 荣光的核心优势是「500 件以上真实施工实绩」。一家展会搭建公司拿库存图当作品展示，是可信度问题，不只是 SEO 问题。Google 的 helpful content 系统对这类信号很敏感。

**③ 可用性** —— Unsplash 的图片 URL 会变更或失效，届时页面直接开天窗。

### 本批做了什么

只做了缓解：给 `images.unsplash.com` 加 `preconnect`，省掉握手开销。

### 本批没做什么

**没有替换图片——这需要你提供真实照片，不是代码能解决的。**

建议优先级：

1. **首屏 hero 图**（`front-page.php`、各服务类目页）——影响 LCP，优先换
2. **10 个服务的主图**——影响可信度
3. `page-works.php` 的案例图——案例库本来就该是实拍

素材到位后告诉我，我改代码路径。

---

## 六、部署

```bash
cd /www/wwwroot/eikoujp.net
bash scripts/server-deploy.sh
wp cache flush --allow-root
bash scripts/seo-verify.sh
```

**不需要** `wp rewrite flush`（本批没有新增路由），**不需要** DeepL key。

### 验收要点

体检脚本应仍是全绿，且**三语 title 现在各不相同**：

```bash
for u in "" "zh/" "en/"; do curl -s "https://eikoujp.net/$u" | grep -oP '<title>\K[^<]*'; done
```

预期：
```
展示会ブース制作・イベント企画・商業空間デザイン｜荣光株式会社
日本展会展台搭建・活动策划・商业空间设计｜荣光株式会社
Exhibition Booth Design & Event Production in Japan | EIKOU Co., Ltd.
```

服务页三语抽查：

```bash
for u in "service-booth-design" "zh/service-booth-design" "en/service-booth-design"; do
  curl -s "https://eikoujp.net/$u/" | grep -oP '<title>\K[^<]*'
done
```

内链区块（应各出现 3 条关联服务链接）：

```bash
curl -s https://eikoujp.net/service-booth-design/ | grep -c 'service-point-card'
```

---

## 七、P1 剩余项（未做）

| 项 | 状态 | 阻塞原因 |
|---|---|---|
| 图片 alt 全量补齐 | 未做 | 建议与图片替换一起做，避免给库存图写实绩 alt |
| Unsplash 图片替换 | 未做 | **需要你提供素材** |
| 关键 CSS 内联 | 未做 | 建议等图片换完再测 CWV，否则基线不准 |
| 视觉面包屑组件化 | 未做 | 结构化数据的 `BreadcrumbList` 已在 P0 完成，视觉层优先级低 |

---

## 八、仍在等你提供的数据

沿用 P0 的清单，提供后我补进结构化数据：

1. 资本金真实数值（`page-about.php:107` 仍是占位符 `XXXX万円`）
2. 法人番号（13 桁）
3. Google Business Profile / SNS 链接 → 用于 `sameAs` 实体串联
4. **服务与案例的真实照片**（本批新增，见 §5）
