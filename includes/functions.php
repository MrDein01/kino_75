<?php
require_once __DIR__ . '/config.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function current_url_path() {
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}

function active_class($file) {
    return basename($_SERVER['SCRIPT_NAME'] ?? '') === $file ? ' is-active' : '';
}

function active_any($files) {
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');
    foreach ((array)$files as $file) {
        if ($current === $file) return ' is-active';
    }
    return '';
}

function redirect_to($path) {
    header('Location: ' . $path);
    exit;
}

function app_session_start() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }

    // On Vercel/PHP serverless any accidental output before session_start breaks headers.
    // Public pages must not print warnings because of that, so session startup becomes safe.
    if (headers_sent()) {
        return false;
    }

    if (session_name() !== 'kultura75_session') {
        session_name('kultura75_session');
    }

    return @session_start();
}

function is_admin_request() {
    $path = current_url_path();
    return strpos($path, '/admin') === 0;
}

function is_admin() {
    if (!app_session_start()) {
        return false;
    }
    return !empty($_SESSION['is_admin']);
}

function require_admin() {
    if (!is_admin()) {
        redirect_to('/admin/login.php');
    }
}

function csrf_token() {
    app_session_start();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf'];
}

function verify_csrf() {
    app_session_start();
    $posted = $_POST['csrf'] ?? '';
    if (!$posted || !hash_equals($_SESSION['csrf'] ?? '', $posted)) {
        http_response_code(400);
        exit('Ошибка безопасности формы. Вернитесь назад и отправьте форму заново.');
    }
}

function format_date_ru($date) {
    if (!$date) return '';
    $months = [
        '01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня',
        '07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'
    ];
    $ts = strtotime($date);
    if ($ts === false) return e($date);
    $d = date('d', $ts);
    $m = $months[date('m', $ts)] ?? date('m', $ts);
    $y = date('Y', $ts);
    return ltrim($d, '0') . ' ' . $m . ' ' . $y;
}

function markdown_light($text) {
    $text = trim((string)$text);
    if ($text === '') return '';
    $escaped = e($text);
    $escaped = preg_replace('/\*\*(.*?)\*\*/u', '<strong>$1</strong>', $escaped);
    $parts = preg_split('/\n\s*\n/u', $escaped);
    $html = [];
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') continue;
        $lines = preg_split('/\n/u', $part);
        $isList = true;
        foreach ($lines as $line) {
            if (!preg_match('/^\s*[-•]\s+/u', $line)) {
                $isList = false;
                break;
            }
        }
        if ($isList) {
            $items = array_map(function($line) {
                return '<li>' . preg_replace('/^\s*[-•]\s+/u', '', trim($line)) . '</li>';
            }, $lines);
            $html[] = '<ul>' . implode('', $items) . '</ul>';
        } else {
            $html[] = '<p>' . nl2br($part) . '</p>';
        }
    }
    return implode("\n", $html);
}

function slugify($text) {
    $map = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
        'А'=>'a','Б'=>'b','В'=>'v','Г'=>'g','Д'=>'d','Е'=>'e','Ё'=>'e','Ж'=>'zh','З'=>'z','И'=>'i','Й'=>'y','К'=>'k','Л'=>'l','М'=>'m','Н'=>'n','О'=>'o','П'=>'p','Р'=>'r','С'=>'s','Т'=>'t','У'=>'u','Ф'=>'f','Х'=>'h','Ц'=>'c','Ч'=>'ch','Ш'=>'sh','Щ'=>'sch','Ъ'=>'','Ы'=>'y','Ь'=>'','Э'=>'e','Ю'=>'yu','Я'=>'ya'
    ];
    $text = strtr($text, $map);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'news';
}

function image_url($path) {
    $path = trim((string)$path);
    if ($path === '') return '/assets/img/placeholder.svg';
    if (preg_match('#^https?://#i', $path)) return $path;
    if (preg_match('#^data:image/#i', $path)) return $path;
    return '/' . ltrim($path, '/');
}

function max_link_or_placeholder() {
    return trim((string)MAX_URL);
}
?>
