# Google Search Console 接入手册

> 目标：让 Google 尽快发现并正确收录三语站点，且**能分语言看数据**。
> 前置：P0 技术地基已线上验收通过（`bash scripts/seo-verify.sh` 全绿）。

---

## 先跑一遍提交前核查

```bash
cd /www/wwwroot/eikoujp.net
bash scripts/seo-gsc-precheck.sh
```

**这一步别跳过。** 带着错误提交，GSC 那边要等好几天才会重新抓取，排查周期会被无谓拉长。

脚本会检查：sitemap 可达性与 XML 合法性、sitemap 内 URL 抽样实测（Google 会逐条抓，死链会被标记为错误）、robots.txt 是否放行、关键页面有无 noindex、DNS TXT 记录是否生效。

---

## 第 1 步 · 验证【域名属性】

**必须用域名属性（Domain property），不能只用 URL 前缀属性。**

域名属性一次覆盖 `http/https`、`www/非www`、以及所有语言前缀路径。URL 前缀属性只认精确匹配的前缀，用它会漏数据。

1. 打开 https://search.google.com/search-console
2. 左上角属性下拉 → **添加资源**
3. 选**左边**那栏「**网域**」（Domain），填 `eikoujp.net`
   > 不要填 `https://eikoujp.net`，域名属性只要裸域名
4. Google 给出一条 TXT 记录，形如：
   ```
   google-site-verification=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   ```
5. 去**域名注册商**的 DNS 管理页添加：

   | 字段 | 值 |
   |---|---|
   | 类型 | `TXT` |
   | 主机记录 / Name | `@`（或留空，视服务商而定） |
   | 记录值 / Value | `google-site-verification=xxxxx...`（整串粘贴） |
   | TTL | 默认即可 |

   > **注意：DNS 在域名注册商那边改，不是在宝塔面板。** 除非你的 DNS 也托管在宝塔。

6. 回服务器确认记录已生效：

   ```bash
   dig +short TXT eikoujp.net
   # 或
   bash scripts/seo-gsc-precheck.sh    # 第 5 节会检查
   ```

   看到 `google-site-verification=...` 就是生效了。**DNS 生效通常要 10 分钟到 2 小时**，没出来先等，别反复点验证。

7. 回 GSC 点「验证」。

---

## 第 2 步 · 提交 sitemap

1. GSC 左侧菜单 → **站点地图**（Sitemaps）
2. 「添加新的站点地图」输入框里填 —— **只填路径，不要填完整 URL**：
   ```
   sitemap-i18n.xml
   ```
3. 点「提交」

**预期结果：** 状态「成功」，已发现网址数 **189**。

如果显示「无法获取」：等 10 分钟刷新（Google 是异步抓取的，提交瞬间常显示此状态）。持续报错就重跑 `seo-gsc-precheck.sh`。

> 也可以顺手把 `wp-sitemap.xml`（WordPress 自带的）一起提交，无害。但主力是 `sitemap-i18n.xml`——只有它带三语和 hreflang 标注。

---

## 第 3 步 · 另建 3 个 URL 前缀属性（**别省这步**）

域名属性只能看**总量**，判断不了「中文页到底有没有起量」——而这正是三语策略成败的唯一衡量指标。

重复「添加资源」，这次选**右边**那栏「网址前缀」，分别添加：

```
https://eikoujp.net/
https://eikoujp.net/zh/
https://eikoujp.net/en/
```

验证方式选「Google Analytics」或「HTML 标记」都行；域名属性已验证过的情况下，通常会自动通过。

之后在这三个属性里分别看「效果」报告，就能独立跟踪日/中/英三个语种的展示次数、点击、平均排名。

---

## 第 4 步 · 后续检查（提交后 1~2 周）

| 时间 | 检查项 | 位置 | 期望 |
|---|---|---|---|
| 1~3 天 | sitemap 状态 | 站点地图 | 「成功」，已发现 189 |
| 3~7 天 | 收录进度 | 网页 → 已编入索引 | 数字持续上升 |
| **1~2 周** | **hreflang 错误** | **旧版工具 → 国际定位** | **无错误** |
| 1~2 周 | 重复内容警告 | 网页 → 未编入索引 | 无「重复，Google 选择了不同的规范网址」 |
| 2~4 周 | 三语分别的流量 | 各 URL 前缀属性 → 效果 | `/zh/` `/en/` 开始有展示 |

**最需要盯的是「国际定位」报告。** 那里如果报 hreflang 错误，说明三语集群没被正确识别，前面所有工作的效果会打对折。

### 加速收录：手动请求编入索引

首页和几个重点服务页可以手动催一下：

1. GSC 顶部搜索框粘贴完整 URL，回车
2. 等待「网址检查」结果
3. 点「**请求编入索引**」

每天有配额限制（约 10 条），优先催这几个：

```
https://eikoujp.net/
https://eikoujp.net/zh/
https://eikoujp.net/en/
https://eikoujp.net/service-booth-design/
https://eikoujp.net/zh/service-booth-design/
https://eikoujp.net/en/service-booth-design/
```

---

## 第 5 步 · Bing + IndexNow（10 分钟，白捡的流量）

### Bing Webmaster Tools

1. 打开 https://www.bing.com/webmasters
2. 选「**从 Google Search Console 导入**」—— 属性和 sitemap 一并带过来，不用重新验证
3. 完事

日本市场 Bing 份额虽小，但这是纯白捡的。

### IndexNow 即时推送

Bing 主导的即时收录协议，Yandex、Seznam、Naver 也支持。推送后通常几小时内抓取。

```bash
cd /www/wwwroot/eikoujp.net
bash scripts/seo-indexnow.sh
```

首次运行会自动生成密钥、创建验证文件、推送 sitemap 里的全部 189 条 URL。之后内容有更新时重跑即可。

只推指定页面：

```bash
bash scripts/seo-indexnow.sh https://eikoujp.net/zh/service-booth-design/
```

挂个每周定时任务（宝塔面板 → 计划任务 → Shell 脚本）：

```bash
0 3 * * 1 bash /www/wwwroot/eikoujp.net/scripts/seo-indexnow.sh >/dev/null 2>&1
```

---

## 关于「有没有命令能直接提交 sitemap 到 Google」

**没有。** 两个常见误区先说清楚：

1. **`google.com/ping?sitemap=` 已经在 2023 年停用**，现在调它没有任何效果。网上大量教程还在教这个，已经过时了。
2. **IndexNow 对 Google 无效**，Google 明确表示不参与该协议。

Google 侧唯一的自动化途径是 **Search Console API**，但它需要配 Google Cloud 项目 + OAuth 服务账号 + 授权，配置成本远高于在网页上点一次「提交」——而 sitemap 提交本来就是**一次性动作**，之后 Google 会自己定期重新抓取 sitemap，不需要每次更新都重新提交。

所以：**Google 用网页点一次，Bing/Yandex 用 `seo-indexnow.sh` 自动化。** 这是目前的最优解。

---

## 提交完成后

回来告诉我这几个数：

- sitemap 状态与「已发现网址数」
- 1~2 周后「国际定位」报告有无 hreflang 错误
- `/zh/` `/en/` 属性的收录页数

有真实数据后，P2（场馆页、展会页程序化扩页）的关键词优先级能排得更准——先看哪些词已经有展示但排名靠后，那些是最容易拿分的。
