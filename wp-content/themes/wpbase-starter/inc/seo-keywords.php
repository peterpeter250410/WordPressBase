<?php
/**
 * SEO Keywords — 逐页 · 逐语种的 title / description 关键词映射
 *
 * 设计说明（重要）：
 * 本文件的文案【不经过 .mo 翻译】，而是按语种直接定义。
 *
 * 原因：SEO 文案不是翻译问题，是各语种独立的关键词研究问题。
 *   日文用户搜「展示会 ブース 制作」
 *   中文用户搜「日本展会搭建」「东京展台设计」
 *   英文用户搜 "exhibition booth design Japan"
 * 这三者互相不是对方的译文。走 gettext 只能得到直译，拿不到目标语种
 * 的真实搜索词，等于白白浪费三语站最值钱的部分。
 *
 * 附带好处：不依赖 DeepL / .po / .mo，改完部署即生效。
 *
 * 字段：
 *   t = title（不含品牌后缀，后缀由 eikou_seo_brand_suffix() 按语种追加）
 *   d = meta description
 *
 * 长度基准（超出会被 seo-core.php 截断）：
 *   title  ja/zh ≤ 32 全角   en ≤ 60
 *   desc   ja/zh ≤ 90 全角   en ≤ 155
 *
 * @package wpbase-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

/** 品牌后缀（按语种） */
function eikou_seo_brand_suffix($lang) {
    $map = [
        'ja' => '｜荣光株式会社',
        'zh' => '｜荣光株式会社',
        'en' => ' | EIKOU Co., Ltd.',
    ];
    return isset($map[$lang]) ? $map[$lang] : $map['ja'];
}

/**
 * 关键词映射表
 * key '_front' 为首页，其余为服务页 slug（与 eikou_get_service_items() 对应）
 */
function eikou_seo_keyword_map() {
    return [

    /* ─── 首页 ─── */
    '_front' => [
        'ja' => [
            't' => '展示会ブース制作・イベント企画・商業空間デザイン',
            'd' => '展示会ブース制作・イベント企画・商業空間デザインをワンストップで提供。東京ビッグサイト・幕張メッセなど主要会場で豊富な実績。日中英の三言語対応で海外出展も支援します。',
        ],
        'zh' => [
            't' => '日本展会展台搭建・活动策划・商业空间设计',
            'd' => '提供日本展会展台设计搭建、活动策划、商业空间设计一站式服务。东京国际展示场、幕张展览馆等主要展馆丰富实绩。日中英三语对应，全程支持中国企业赴日参展。',
        ],
        'en' => [
            't' => 'Exhibition Booth Design & Event Production in Japan',
            'd' => 'Exhibition booth design, construction and event production across Japan. Proven at Tokyo Big Sight and Makuhari Messe. Trilingual JP/CN/EN support.',
        ],
    ],

    /* ─── A. 展示会・イベント（7） ─── */
    'service-booth-design' => [
        'ja' => ['t' => '展示会ブース制作・デザイン会社｜設計から施工まで',
                 'd' => '東京ビッグサイト・幕張メッセなど全国主要会場で500件以上の施工実績。ブースデザイン企画から3Dパース、設営・撤去まで一括で対応します。'],
        'zh' => ['t' => '日本展会展台设计搭建｜东京展台制作公司',
                 'd' => '提供日本展会展台设计与施工一站式服务。东京国际展示场、幕张展览馆等主要展馆500件以上搭建实绩，从3D效果图到现场撤展全程负责。'],
        'en' => ['t' => 'Exhibition Booth Design & Construction in Japan',
                 'd' => 'Exhibition booth design and construction across Japan. 500+ builds at Tokyo Big Sight, Makuhari Messe and major venues, from 3D renders to teardown.'],
    ],
    'service-display-fixtures' => [
        'ja' => ['t' => '展示什器・ディスプレイ什器のオーダーメイド制作',
                 'd' => 'アクリル・ステンレス・アルミ・木材など多素材に対応。製品形状に合わせた完全オーダーメイド設計で、リユース可能な高耐久什器を制作します。'],
        'zh' => ['t' => '日本展示道具・展架定制制作',
                 'd' => '亚克力、不锈钢、铝材、木材等多材质对应。按产品形状完全定制设计，打造可重复使用的高耐久展示道具，降低长期参展成本。'],
        'en' => ['t' => 'Custom Display Fixtures & Props in Japan',
                 'd' => 'Made-to-order display fixtures in acrylic, steel, aluminium and wood. Custom-engineered to your product and built for reuse across multiple shows.'],
    ],
    'service-steel-structure' => [
        'ja' => ['t' => '展示会用 鉄骨・アルミ構造の設計加工｜構造計算対応',
                 'd' => '大型ブース・特殊構造物の鉄骨/アルミフレームを自社工場で一貫制作。構造計算書の作成と第三者検証で、大規模構造物も安全に施工します。'],
        'zh' => ['t' => '日本展会钢结构・铝合金结构加工制作',
                 'd' => '大型展台与特殊构造物的钢结构、铝合金框架由自有工厂一贯制作。提供结构计算书与第三方安全验证，大规模构造也能安心施工。'],
        'en' => ['t' => 'Steel & Aluminium Structures for Exhibitions',
                 'd' => 'In-house fabrication of steel and aluminium frames for large booths and special structures, with structural calculations and third-party verification.'],
    ],
    'service-woodwork' => [
        'ja' => ['t' => '展示会造作の木工制作・塗装・焼付塗装加工',
                 'd' => '無垢材から合板・MDFまでCNC精密加工に対応。ウレタン・ラッカー・焼付塗装のほか、エイジングや鏡面など特殊仕上げも職人が手仕上げします。'],
        'zh' => ['t' => '日本展会木工制作・涂装・烤漆加工',
                 'd' => '从实木到胶合板、MDF均可CNC精密加工。聚氨酯漆、硝基漆、烤漆及做旧、镜面等特殊工艺，由熟练工匠手工完成。'],
        'en' => ['t' => 'Woodwork, Painting & Baked Enamel Finishes',
                 'd' => 'CNC-precision woodwork in solid timber, plywood and MDF with urethane, lacquer and baked-enamel finishes. Specialty and mirror finishes hand-completed.'],
    ],
    'service-led-av' => [
        'ja' => ['t' => 'LEDディスプレイ・照明・音響機材のレンタル設置',
                 'd' => '高精細P2.5以下のLEDパネルから4Kプロジェクターまで自社保有。経験豊富な技術スタッフが設営から現場オペレーションまで担当します。'],
        'zh' => ['t' => '日本LED屏・灯光・音响设备租赁安装',
                 'd' => '自有P2.5以下高清LED屏至4K投影机等设备。经验丰富的技术团队负责现场安装与操作，从小型研讨会到大型会展均可对应。'],
        'en' => ['t' => 'LED Display, Lighting & AV Equipment Rental',
                 'd' => 'Owned inventory from P2.5 fine-pitch LED panels to 4K projectors. Technical staff handle installation and on-site operation for events of any scale.'],
    ],
    'service-logistics' => [
        'ja' => ['t' => '展示会資材の倉庫保管・輸送・搬入搬出',
                 'd' => '温湿度管理された自社倉庫で展示資材を保管。北海道から沖縄まで全国配送し、複数会場をまわる巡回展示の物流も一括コーディネートします。'],
        'zh' => ['t' => '日本展会物资仓储・运输・进出场管理',
                 'd' => '自有恒温恒湿仓库保管展示物资。配送网络覆盖北海道至冲绳，多会场巡回展出的物流计划与执行也可一并统筹。'],
        'en' => ['t' => 'Exhibition Logistics, Storage & Installation',
                 'd' => 'Climate-controlled warehousing for exhibition assets, nationwide delivery from Hokkaido to Okinawa, and logistics for multi-venue touring exhibitions.'],
    ],
    'service-onsite-ops' => [
        'ja' => ['t' => '展示会の現場運営・プロジェクト管理代行',
                 'd' => '年間100件以上を管理するPMが統括。日中英対応スタッフの配置と、来場者データの集計・分析レポートで出展効果の改善まで支援します。'],
        'zh' => ['t' => '日本展会现场运营・项目管理代理',
                 'd' => '由年管理100场以上展会的项目经理统筹。配置中日英三语工作人员，并提供参观者数据统计分析报告，助力下次参展改进。'],
        'en' => ['t' => 'On-Site Event Operations & Project Management',
                 'd' => 'Led by project managers running 100+ exhibitions a year. Trilingual staffing in Japanese, Chinese and English, plus visitor data analysis after the show.'],
    ],

    /* ─── B. ブランドイベント（5） ─── */
    'service-launch-event' => [
        'ja' => ['t' => '新製品発表会・ブランドイベントの企画運営',
                 'd' => '企画立案から会場手配、演出、当日運営までワンストップ。プレスリリース配信とメディアリレーション構築で、露出の最大化まで支援します。'],
        'zh' => ['t' => '日本新品发布会・品牌活动策划执行',
                 'd' => '从策划立案、场地安排、舞台演出到当天运营一站式承接。同时提供新闻稿发布与媒体关系建设，最大化活动曝光。'],
        'en' => ['t' => 'Product Launches & Brand Events in Japan',
                 'd' => 'End-to-end planning, venue sourcing, staging and day-of operations, with press release distribution and media relations to maximise coverage.'],
    ],
    'service-popup-store' => [
        'ja' => ['t' => 'ポップアップストア・商業空間の企画施工',
                 'd' => '期間限定店舗からブランド常設空間まで、コンセプト設計・内装施工・什器制作を一括対応。集客動線と体験設計まで踏み込んで提案します。'],
        'zh' => ['t' => '日本快闪店・商业空间企划施工',
                 'd' => '从限时快闪店到品牌常设空间，概念设计、内装施工、道具制作一并承接。深入到客流动线与体验设计层面提案。'],
        'en' => ['t' => 'Pop-up Stores & Commercial Space Build-out',
                 'd' => 'From limited-run pop-ups to permanent brand spaces: concept design, interior construction and fixture fabrication under one roof, plus traffic flow design.'],
    ],
    'service-brand-promotion' => [
        'ja' => ['t' => '企業ブランドプロモーション・マーケティング支援',
                 'd' => 'ブランド戦略の策定から、イベント・SNS・広告を組み合わせた統合プロモーションの実行までを支援。日本市場での認知拡大を後押しします。'],
        'zh' => ['t' => '日本市场品牌推广・营销活动执行',
                 'd' => '从品牌战略制定，到整合线下活动、社交媒体与广告投放的推广执行。面向日本消费者与B2B客户，助力海外企业在日本市场快速建立认知度与信赖感。'],
        'en' => ['t' => 'Brand Promotion & Marketing Campaigns',
                 'd' => 'From brand strategy through integrated campaigns spanning events, social media and advertising, built to grow awareness in the Japanese market.'],
    ],
    'service-nationwide' => [
        'ja' => ['t' => '日本全国の展示会・イベント施工に対応',
                 'd' => '東京・大阪・名古屋・福岡・札幌など全国主要会場に対応。地方開催や複数会場の同時展開も、統一した品質で施工・運営します。'],
        'zh' => ['t' => '日本全国展会・活动施工对应',
                 'd' => '对应东京、大阪、名古屋、福冈、札幌等全国主要会场。地方举办及多会场同时展开也能保持统一品质施工运营。'],
        'en' => ['t' => 'Nationwide Exhibition & Event Execution',
                 'd' => 'Major venues in Tokyo, Osaka, Nagoya, Fukuoka and Sapporo. Regional shows and simultaneous multi-venue rollouts delivered to one quality standard.'],
    ],
    'service-japan-entry' => [
        'ja' => ['t' => '海外企業の日本市場参入・展示会出展支援',
                 'd' => '日本の商習慣・会場規則・行政手続きに精通したチームが、出展手配から現地施工、当日運営までを代行。日中英の三言語で対応します。'],
        'zh' => ['t' => '中国企业赴日参展・日本市场进入支援',
                 'd' => '熟悉日本商务惯例、场馆规则与行政手续的团队，代办参展申请、现地施工到当天运营。中日英三语沟通，无需在日本设点也能顺利参展。'],
        'en' => ['t' => 'Japan Market Entry & Exhibiting Support',
                 'd' => 'A team fluent in Japanese business practice, venue regulations and paperwork handles your exhibition application, on-site build and day-of operations.'],
    ],

    /* ─── C. デジタル・Web（3） ─── */
    'service-web-design' => [
        'ja' => ['t' => '企業Webサイトのデザイン・制作',
                 'd' => 'ブランドイメージを反映したコーポレートサイトを企画から設計・開発まで。多言語対応・スマートフォン最適化・SEO設計を標準で組み込みます。'],
        'zh' => ['t' => '日本企业官网设计・开发',
                 'd' => '从企划到设计开发，打造体现品牌形象的日本企业官网。日中英多语言对应、移动端优化与SEO架构为标准配置，兼顾日本市场的设计审美与使用习惯。'],
        'en' => ['t' => 'Corporate Web Design & Development',
                 'd' => 'Corporate websites built from concept to launch. Multilingual support, mobile optimisation and SEO architecture included as standard.'],
    ],
    'service-web-marketing' => [
        'ja' => ['t' => 'Webサイト最適化・デジタルマーケティング支援',
                 'd' => 'SEO内部施策、コンテンツ設計、アクセス解析にもとづく改善までを継続支援。日本語・中国語・英語の多言語SEOにも対応します。'],
        'zh' => ['t' => '网站优化・数字营销（日本市场）',
                 'd' => '从SEO站内优化、内容架构到基于数据分析的持续改进。支持日语、中文、英语的多语言SEO，面向日本市场获客。'],
        'en' => ['t' => 'SEO & Digital Marketing for Japan',
                 'd' => 'Technical SEO, content architecture and analytics-driven iteration. Multilingual SEO across Japanese, Chinese and English for brands targeting Japan.'],
    ],
    'service-app-dev' => [
        'ja' => ['t' => 'スマートフォンアプリ・業務アプリ開発',
                 'd' => 'iOS/Androidアプリから業務システムまで、要件定義・設計・開発・保守を一貫対応。展示会やイベントと連動した体験型アプリも制作します。'],
        'zh' => ['t' => '日本App开发・业务系统开发',
                 'd' => '从iOS/Android应用到企业业务系统，需求定义、设计、开发、维护一贯对应。也可制作与展会活动联动的体验型应用。'],
        'en' => ['t' => 'Mobile & Business Application Development',
                 'd' => 'iOS, Android and internal business systems: requirements, design, development and maintenance in one team, including interactive apps tied to exhibitions.'],
    ],

    /* ─── D. AIソリューション（3） ─── */
    'service-ai-chatbot' => [
        'ja' => ['t' => 'AIチャットボットの導入・運用支援',
                 'd' => '問い合わせ対応の自動化から多言語接客まで、既存業務に合わせてAIチャットボットを設計・導入。運用開始後のチューニングも支援します。'],
        'zh' => ['t' => 'AI智能客服机器人导入・运营支援',
                 'd' => '从咨询自动应答到日中英多语言接待，按现有业务流程设计并导入AI客服机器人。降低人工客服负担、提升响应速度，上线后的持续调优也一并支持。'],
        'en' => ['t' => 'AI Chatbot Implementation & Support',
                 'd' => 'AI chatbots designed around your existing workflow, from automated enquiry handling to multilingual customer service, with post-launch tuning included.'],
    ],
    'service-ai-modeling' => [
        'ja' => ['t' => 'AIモデリング・AIアプリケーション開発',
                 'd' => '画像認識・自然言語処理などのAIモデル構築から、業務に組み込むアプリケーション開発まで対応。3Dモデル生成の実務活用もご提案します。'],
        'zh' => ['t' => 'AI建模・AI应用开发',
                 'd' => '从图像识别、自然语言处理等AI模型构建，到嵌入业务流程的应用开发。也可提案3D模型生成的实际应用方案。'],
        'en' => ['t' => 'AI Modeling & Application Development',
                 'd' => 'From building image-recognition and NLP models to embedding them in working applications, including practical use of AI-driven 3D model generation.'],
    ],
    'service-automation' => [
        'ja' => ['t' => '業務自動化・カスタマーサポート自動化',
                 'd' => '定型業務の自動化から問い合わせ対応の効率化まで、AIとRPAを組み合わせて設計。人手不足の解消とコスト削減を同時に実現します。'],
        'zh' => ['t' => '业务自动化・客服自动化系统',
                 'd' => '从常规业务自动化到客户咨询响应提效，结合AI与RPA进行系统设计与落地。缓解日本企业普遍面临的人手不足问题，同时实现长期运营成本下降。'],
        'en' => ['t' => 'Business & Customer Support Automation',
                 'd' => 'AI and RPA combined to automate routine operations and streamline customer enquiries, addressing staffing shortages and cost pressure at the same time.'],
    ],

    /* ─── E. ブランディング・デザイン（3） ─── */
    'service-package-design' => [
        'ja' => ['t' => 'ブランドパッケージ・商品パッケージデザイン',
                 'd' => '商品の世界観を伝えるパッケージを、コンセプト設計から量産データ作成まで一貫制作。素材選定や印刷加工の指定までサポートします。'],
        'zh' => ['t' => '品牌包装・商品包装设计（日本）',
                 'd' => '从概念设计到量产印刷数据制作，一贯打造传达商品世界观的品牌包装。材质选定、印刷工艺与后加工指定也一并支持，确保量产品质稳定。'],
        'en' => ['t' => 'Brand & Product Package Design',
                 'd' => "Packaging that carries your product's world view, from concept through production-ready artwork, including material selection and print finishing."],
    ],
    'service-brand-pr' => [
        'ja' => ['t' => 'ブランド・製品プロモーションの企画実行',
                 'd' => 'ブランドストーリーの設計から、製品発表・メディア露出・体験施策までを一貫して企画・実行。日本市場での想起率向上を目指します。'],
        'zh' => ['t' => '品牌・产品推广企划执行',
                 'd' => '从品牌故事设计，到产品发布、媒体曝光与体验式营销的一贯企划执行。以提升在日本市场的品牌联想度为目标。'],
        'en' => ['t' => 'Brand & Product Promotion',
                 'd' => 'Brand storytelling through to product launches, media exposure and experiential activations, planned and executed to lift brand recall in Japan.'],
    ],
    'service-print-design' => [
        'ja' => ['t' => 'ポスター・パンフレット・印刷物のデザイン制作',
                 'd' => '展示会ツールから会社案内まで、目的に合わせた印刷物をデザイン。入稿データ作成から印刷手配まで一括で対応します。'],
        'zh' => ['t' => '海报・宣传册等印刷品设计制作',
                 'd' => '从展会宣传物料、海报到公司简介手册，按使用场景与目标受众设计各类印刷品。制版数据制作、印刷厂对接与交期管理一并承接。'],
        'en' => ['t' => 'Poster, Brochure & Print Design',
                 'd' => 'Print collateral designed for purpose, from exhibition handouts to company profiles, with print-ready artwork and production management handled end to end.'],
    ],

    /* ─── F. メディア・映像（3） ─── */
    'service-media-ops' => [
        'ja' => ['t' => 'SNS・オウンドメディアの運営代行',
                 'd' => 'SNSアカウントの企画・投稿制作・運用代行から、オウンドメディアのコンテンツ設計まで。日本語・中国語・英語での発信に対応します。'],
        'zh' => ['t' => '社交媒体・自有媒体运营代理',
                 'd' => '从社交账号企划、内容制作到日常运营代理，以及自有媒体的内容架构设计。支持日语、中文、英语三语发布，覆盖日本本土与海外华人受众。'],
        'en' => ['t' => 'Social & Owned Media Management',
                 'd' => 'Social account strategy, content production and day-to-day management, plus owned-media content architecture, publishing in Japanese, Chinese and English.'],
    ],
    'service-video-production' => [
        'ja' => ['t' => '企業動画・アニメーション制作',
                 'd' => '会社紹介・製品PR・展示会用映像まで、企画構成から撮影・編集・アニメーション制作まで一貫対応。多言語字幕の制作にも対応します。'],
        'zh' => ['t' => '日本企业宣传片・动画制作',
                 'd' => '公司介绍、产品宣传到展会用循环播放影像，从企划构成到拍摄、剪辑、动画制作一贯对应。支持日中英多语言字幕与配音，一次拍摄多语种发布。'],
        'en' => ['t' => 'Corporate Video & Animation Production',
                 'd' => 'Company profiles, product films and exhibition content: concept, shooting, editing and animation handled in-house, with multilingual subtitling available.'],
    ],
    'service-signage' => [
        'ja' => ['t' => '大判印刷・UVプリント・サイン看板制作',
                 'd' => '最新の大判インクジェットプリンターで高解像度出力。紙・布・アクリル・アルミ・ガラスなど多素材に対応し、現場設置まで一括対応します。'],
        'zh' => ['t' => '大幅面印刷・UV打印・标识招牌制作',
                 'd' => '采用最新大幅面喷墨打印机高分辨率输出。对应纸张、布料、亚克力、铝材、玻璃等多种材质，并承接现场安装施工。'],
        'en' => ['t' => 'Large Format Print, UV Printing & Signage',
                 'd' => 'High-resolution output from the latest large-format inkjet presses. Printing on paper, fabric, acrylic, aluminium and glass, with on-site installation.'],
    ],

    ];
}

/**
 * 取指定 key 在当前（或指定）语种下的 SEO 文案
 *
 * @param string      $key  '_front' 或服务页 slug
 * @param string|null $lang 语种，默认当前语种
 * @return array|null ['t' => string, 'd' => string]
 */
function eikou_seo_keyword_entry($key, $lang = null) {
    $map = eikou_seo_keyword_map();
    if (!isset($map[$key])) {
        return null;
    }
    if ($lang === null) {
        $lang = eikou_current_lang();
    }
    // 缺某语种时回退日文，保证永远有值
    if (isset($map[$key][$lang])) {
        return $map[$key][$lang];
    }
    return isset($map[$key]['ja']) ? $map[$key]['ja'] : null;
}

/** 当前页面对应的关键词条目（首页 / 服务页），无匹配返回 null */
function eikou_seo_current_keyword_entry() {
    if (is_front_page()) {
        return eikou_seo_keyword_entry('_front');
    }
    if (is_page()) {
        $slug = get_post_field('post_name', get_queried_object_id());
        return eikou_seo_keyword_entry($slug);
    }
    return null;
}
