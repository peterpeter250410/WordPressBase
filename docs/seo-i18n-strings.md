# SEO 新增文案的三语译文（手工填入用）

> 本批 SEO 改动只新增了 **3 条**可翻译日文串，无需动用 DeepL。
> 直接把下面的 `msgstr` 填进 `.po` 文件后重新编译 `.mo` 即可。

---

## 为什么建议手工翻而不是机翻

这 3 条里有 2 条是**首页的 title 和 meta description** —— 全站最重要的两个关键词位。机器翻译会把日文关键词直译成中英文，但中文搜「日本展会搭建」、英文搜 `exhibition booth Japan`，用的根本不是日文词的直译。**这里需要的是各语种独立的关键词布局，不是翻译。**

下面的译文按各语种真实搜索词写，不是直译。

---

## 1. 首页 title

**日文原文（msgid）**
```
展示会ブース制作・イベント企画・商業空間デザイン
```

| 语种 | 译文 | 关键词考量 |
|---|---|---|
| 中文 | `日本展会展台搭建・活动策划・商业空间设计` | 主攻「日本展会搭建」「展台设计」，加「日本」限定词是关键——中文用户搜的是「日本展会」而非泛指展会 |
| 英文 | `Exhibition Booth Design & Event Production in Japan` | 主攻 `exhibition booth design Japan`、`event production Japan`，`in Japan` 必须保留 |

---

## 2. 首页 meta description

**日文原文（msgid）** — 82 字符，在日文搜索结果显示上限内
```
展示会ブース制作・イベント企画・商業空間デザインをワンストップで提供。東京ビッグサイト・幕張メッセなど主要会場で豊富な実績。日中英の三言語対応で海外出展も支援します。
```

**中文译文**（79 字符，上限 90）
```
提供日本展会展台设计搭建、活动策划、商业空间设计一站式服务。在东京国际展示场、幕张展览馆等日本主要展馆拥有丰富实绩。日中英三语对应，全程支持中国企业赴日参展。
```
> 「全程支持中国企业赴日参展」是针对中文客群加的——这是他们真正关心的点，日文原文里没有对应句。

**英文译文**（152 字符，上限 155）
```
End-to-end exhibition booth design and construction, event production and commercial space design in Japan. Proven track record at Tokyo Big Sight, Makuhari Messe and major venues. Trilingual support in Japanese, Chinese and English.
```
> 英文可用长度比日文多得多（155 vs 90），所以展开写全，把 `Tokyo Big Sight`、`Makuhari Messe` 这些高价值场馆词都放进去。

---

## 3. 案例页标题后缀

**日文原文（msgid）**
```
｜施工事例
```

| 语种 | 译文 |
|---|---|
| 中文 | `｜施工案例` |
| 英文 | ` \| Case Study` |

> 英文的竖线前后要有半角空格，且在 `.po` 文件里 `|` 不需要转义，上表中的反斜杠仅为 Markdown 表格显示所需。

---

## 填入方法

### 步骤 1：打开译文文件

```bash
cd /www/wwwroot/eikoujp.net/wp-content/themes/wpbase-starter/languages
vi eikou-zh_CN.po
vi eikou-en_US.po
```

### 步骤 2：追加条目

如果文件里搜不到对应的 `msgid`（新增串第一次出现时就是这样），**直接追加到文件末尾**即可：

**`eikou-zh_CN.po` 末尾追加：**
```po
msgid "展示会ブース制作・イベント企画・商業空間デザイン"
msgstr "日本展会展台搭建・活动策划・商业空间设计"

msgid "展示会ブース制作・イベント企画・商業空間デザインをワンストップで提供。東京ビッグサイト・幕張メッセなど主要会場で豊富な実績。日中英の三言語対応で海外出展も支援します。"
msgstr "提供日本展会展台设计搭建、活动策划、商业空间设计一站式服务。在东京国际展示场、幕张展览馆等日本主要展馆拥有丰富实绩。日中英三语对应，全程支持中国企业赴日参展。"

msgid "｜施工事例"
msgstr "｜施工案例"
```

**`eikou-en_US.po` 末尾追加：**
```po
msgid "展示会ブース制作・イベント企画・商業空間デザイン"
msgstr "Exhibition Booth Design & Event Production in Japan"

msgid "展示会ブース制作・イベント企画・商業空間デザインをワンストップで提供。東京ビッグサイト・幕張メッセなど主要会場で豊富な実績。日中英の三言語対応で海外出展も支援します。"
msgstr "End-to-end exhibition booth design and construction, event production and commercial space design in Japan. Proven track record at Tokyo Big Sight, Makuhari Messe and major venues. Trilingual support in Japanese, Chinese and English."

msgid "｜施工事例"
msgstr " | Case Study"
```

> **注意：** `msgid` 必须与代码里的日文**一字不差**（含全角标点 `・` `。` `｜`），差一个字符就匹配不上。建议直接复制上面的代码块，不要手打。

### 步骤 3：重新编译 `.mo`

```bash
cd /www/wwwroot/eikoujp.net
wp i18n make-mo wp-content/themes/wpbase-starter/languages --allow-root
```

这一步**不调用任何翻译接口**，纯本地编译，不需要 DeepL key。

### 步骤 4：验证

```bash
curl -s https://eikoujp.net/     | grep -oP '<title>\K[^<]*'
curl -s https://eikoujp.net/zh/  | grep -oP '<title>\K[^<]*'
curl -s https://eikoujp.net/en/  | grep -oP '<title>\K[^<]*'
```

预期输出三种语言各不相同的标题。

---

## 如果以后还是想用 DeepL

新增文案多起来之后，手工维护会变麻烦，那时再配 key 走自动流程：

```bash
cd /www/wwwroot/eikoujp.net
DEEPL_API_KEY=你的key:fx bash scripts/i18n-make-translations.sh
```

脚本会**跳过已翻译的条目、只翻新增的**，所以你现在手工填的这 3 条不会被机翻覆盖。

DeepL key 在 https://www.deepl.com/account/summary 的 **Account → API keys** 页面可以直接查看已有 key 或新建。
