<?php
/**
 * EIKOU — Contact Form 7 フォーム一括セットアップスクリプト
 *
 * 実行内容:
 *   1.「完整表单」「快捷表单」を作成 or 更新（フォーム本文 + メール設定込み）
 *   2. テーマカスタマイザーに両フォームのショートコードを自動設定
 *      (eikou_cf7_full / eikou_cf7_quick)
 *
 * 前提: Contact Form 7 プラグインが有効化されていること
 *
 * 使い方（WordPress ルートディレクトリで実行）:
 *   wp eval-file scripts/setup-cf7.php --allow-root
 *
 * ※ タイトルで既存フォームを検索して更新するため、繰り返し実行しても
 *    重複は作られません（冪等）。手動で別タイトルで作成済みの場合は、
 *    そのフォームを削除してから実行してください。
 */

if (!defined('ABSPATH')) { exit; }

if (!class_exists('WPCF7_ContactForm')) {
    echo "[ERROR] Contact Form 7 が有効化されていません。先にプラグインを有効化してください。\n";
    return;
}

$domain    = parse_url(home_url(), PHP_URL_HOST);
$from      = '荣光 <wordpress@' . $domain . '>';
$recipient = function_exists('eikou_get')
    ? eikou_get('eikou_email', get_option('admin_email'))
    : get_option('admin_email');

/* ---------- フォーム本文 ---------- */
$full_form = <<<'FORM'
<p>会社名 *<br>
[text* company placeholder "株式会社XXXX"]</p>

<p>ご担当者名 *<br>
[text* your-name placeholder "山田 太郎"]</p>

<p>メールアドレス *<br>
[email* your-email placeholder "example@company.com"]</p>

<p>電話番号<br>
[tel your-phone placeholder "03-XXXX-XXXX"]</p>

<p>ご相談内容 *<br>
[select* service "選択してください" "展示会・展覧会" "ブランドイベント・発表会" "商業空間・ショールーム" "デジタル・AIソリューション" "その他"]</p>

<p>メッセージ *<br>
[textarea* your-message placeholder "プロジェクトの概要、ご予算、スケジュール等"]</p>

<p>[submit "送信する"]</p>
FORM;

$quick_form = <<<'FORM'
<p>[text* your-name placeholder "お名前"]</p>
<p>[text* your-contact placeholder "電話番号 / メール"]</p>
<p>[submit "送信する"]</p>
FORM;

/* ---------- メール設定 ---------- */
$full_mail = [
    'subject'            => 'お問い合わせ - [your-name]',
    'sender'             => $from,
    'recipient'          => $recipient,
    'additional_headers' => 'Reply-To: [your-email]',
    'body'               => "お問い合わせがありました。\n\n"
        . "会社名: [company]\n"
        . "ご担当者名: [your-name]\n"
        . "メール: [your-email]\n"
        . "電話: [your-phone]\n"
        . "ご相談内容: [service]\n\n"
        . "メッセージ:\n[your-message]\n\n"
        . "--\n[_site_title] ([_site_url])",
    'attachments'        => '',
    'use_html'           => false,
    'exclude_blank'      => false,
];

$quick_mail = [
    'subject'            => 'クイックお問い合わせ - [your-name]',
    'sender'             => $from,
    'recipient'          => $recipient,
    'additional_headers' => '',
    'body'               => "クイックお問い合わせがありました。\n\n"
        . "お名前: [your-name]\n"
        . "連絡先: [your-contact]\n\n"
        . "--\n[_site_title] ([_site_url])",
    'attachments'        => '',
    'use_html'           => false,
    'exclude_blank'      => false,
];

/* ---------- upsert ヘルパー（タイトルで検索 → 更新 or 新規） ---------- */
function eikou_upsert_cf7($title, $form_str, $mail) {
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

$full_id  = eikou_upsert_cf7('完整表单', $full_form, $full_mail);
$quick_id = eikou_upsert_cf7('快捷表单', $quick_form, $quick_mail);

/* ---------- カスタマイザーにショートコードを設定 ---------- */
set_theme_mod('eikou_cf7_full',  sprintf('[contact-form-7 id="%d" title="完整表单"]', $full_id));
set_theme_mod('eikou_cf7_quick', sprintf('[contact-form-7 id="%d" title="快捷表单"]', $quick_id));

echo "==============================\n";
echo "[OK] 完整表单  ID = {$full_id}\n";
echo "[OK] 快捷表单  ID = {$quick_id}\n";
echo "[OK] 送信元    = {$from}\n";
echo "[OK] 送信先    = {$recipient}\n";
echo "[OK] カスタマイザーにショートコードを設定しました。\n";
echo "==============================\n";
echo "完了。/contact/ と H5 フローティングフォームで動作確認してください。\n";
