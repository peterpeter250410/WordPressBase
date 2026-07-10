<?php
/**
 * EIKOU — 多语言 Contact Form 7 表单一键创建（日/中/英）
 * 创建 完整表单 + 快捷表单 各三语，并写入主题定制器：
 *   eikou_cf7_full   / eikou_cf7_full_zh   / eikou_cf7_full_en
 *   eikou_cf7_quick  / eikou_cf7_quick_zh  / eikou_cf7_quick_en
 *
 * 用法（WordPress 根目录）: wp eval-file scripts/i18n-cf7.php --allow-root
 * 前提: Contact Form 7 已启用
 */
if (!class_exists('WPCF7_ContactForm')) { echo "[ERROR] Contact Form 7 未启用\n"; return; }

$domain    = parse_url(home_url(), PHP_URL_HOST);
$from      = '荣光 <wordpress@' . $domain . '>';
$recipient = function_exists('eikou_get') ? eikou_get('eikou_email', get_option('admin_email')) : get_option('admin_email');

/* ---------- 三语表单本文 ---------- */
$full = [
'ja' => <<<'F'
<p>会社名 *<br>[text* company placeholder "株式会社XXXX"]</p>
<p>ご担当者名 *<br>[text* your-name placeholder "山田 太郎"]</p>
<p>メールアドレス *<br>[email* your-email placeholder "example@company.com"]</p>
<p>電話番号<br>[tel your-phone placeholder "03-XXXX-XXXX"]</p>
<p>ご相談内容 *<br>[select* service "選択してください" "展示会・展覧会" "ブランドイベント・発表会" "商業空間・ショールーム" "デジタル・AIソリューション" "その他"]</p>
<p>メッセージ *<br>[textarea* your-message placeholder "プロジェクトの概要、ご予算、スケジュール等"]</p>
<p>[submit "送信する"]</p>
F,
'zh' => <<<'F'
<p>公司名称 *<br>[text* company placeholder "XX有限公司"]</p>
<p>负责人姓名 *<br>[text* your-name placeholder "张三"]</p>
<p>电子邮箱 *<br>[email* your-email placeholder "example@company.com"]</p>
<p>电话号码<br>[tel your-phone placeholder "手机 / 固话"]</p>
<p>咨询内容 *<br>[select* service "请选择" "展会・展览" "品牌活动・发布会" "商业空间・展厅" "数字化・AI解决方案" "其他"]</p>
<p>留言 *<br>[textarea* your-message placeholder "请简述项目概要、预算、时间等"]</p>
<p>[submit "提交"]</p>
F,
'en' => <<<'F'
<p>Company Name *<br>[text* company placeholder "Your Company"]</p>
<p>Contact Person *<br>[text* your-name placeholder "John Smith"]</p>
<p>Email *<br>[email* your-email placeholder "example@company.com"]</p>
<p>Phone<br>[tel your-phone placeholder "Phone"]</p>
<p>Inquiry Type *<br>[select* service "Please select" "Exhibition" "Brand Event / Launch" "Commercial Space / Showroom" "Digital / AI Solution" "Other"]</p>
<p>Message *<br>[textarea* your-message placeholder "Project overview, budget, schedule, etc."]</p>
<p>[submit "Send"]</p>
F,
];

$quick = [
'ja' => "<p>[text* your-name placeholder \"お名前\"]</p>\n<p>[text* your-contact placeholder \"電話番号 / メール\"]</p>\n<p>[submit \"送信する\"]</p>",
'zh' => "<p>[text* your-name placeholder \"姓名\"]</p>\n<p>[text* your-contact placeholder \"电话 / 邮箱\"]</p>\n<p>[submit \"提交\"]</p>",
'en' => "<p>[text* your-name placeholder \"Name\"]</p>\n<p>[text* your-contact placeholder \"Phone / Email\"]</p>\n<p>[submit \"Send\"]</p>",
];

$subject = ['ja' => 'お問い合わせ', 'zh' => '网站咨询', 'en' => 'Website Inquiry'];

/* ---------- upsert：按标题查找 → 更新/新建 ---------- */
function eikou_cf7_upsert($title, $form_str, $mail) {
    $existing = null;
    foreach (WPCF7_ContactForm::find(['posts_per_page' => -1]) as $cf) {
        if ($cf->title() === $title) { $existing = $cf; break; }
    }
    $cf = $existing ? $existing : WPCF7_ContactForm::get_template();
    $cf->set_title($title);
    $props = $cf->get_properties();
    $props['form'] = $form_str;
    $props['mail'] = array_merge((array) $props['mail'], $mail);
    $cf->set_properties($props);
    $cf->save();
    return $cf->id();
}

$langs = ['ja' => '日', 'zh' => '中', 'en' => '英'];
foreach ($langs as $L => $label) {
    // 完整表单
    $full_mail = [
        'subject'            => $subject[$L] . ' - [your-name]',
        'sender'             => $from,
        'recipient'          => $recipient,
        'additional_headers' => 'Reply-To: [your-email]',
        'body'               => "[{$label}] お問い合わせ\n\n会社名: [company]\n担当: [your-name]\nメール: [your-email]\n電話: [your-phone]\n内容: [service]\n\n[your-message]\n\n--\n[_site_title] ([_site_url])",
        'attachments'        => '', 'use_html' => false, 'exclude_blank' => false,
    ];
    $full_id = eikou_cf7_upsert("完整表单-{$label}", $full[$L], $full_mail);

    // 快捷表单
    $quick_mail = [
        'subject'            => $subject[$L] . '（快捷） - [your-name]',
        'sender'             => $from,
        'recipient'          => $recipient,
        'additional_headers' => '',
        'body'               => "[{$label}] クイックお問い合わせ\n\nお名前: [your-name]\n連絡先: [your-contact]\n\n--\n[_site_title] ([_site_url])",
        'attachments'        => '', 'use_html' => false, 'exclude_blank' => false,
    ];
    $quick_id = eikou_cf7_upsert("快捷表单-{$label}", $quick[$L], $quick_mail);

    // 写入定制器（ja 无后缀，zh/en 带后缀）
    $suffix = ($L === 'ja') ? '' : '_' . $L;
    set_theme_mod('eikou_cf7_full'  . $suffix, sprintf('[contact-form-7 id="%d" title="完整表单-%s"]', $full_id, $label));
    set_theme_mod('eikou_cf7_quick' . $suffix, sprintf('[contact-form-7 id="%d" title="快捷表单-%s"]', $quick_id, $label));

    echo "[OK] {$label}: 完整={$full_id} 快捷={$quick_id}\n";
}

echo "==============================\n";
echo "[完成] 三语 CF7 表单已创建并写入定制器。\n";
echo "送信元={$from}  送信先={$recipient}\n";
echo "==============================\n";
