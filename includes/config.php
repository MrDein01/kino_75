<?php
function config_env($name, $default = '') {
    $value = getenv($name);
    if ($value === false || $value === '') {
        return $default;
    }
    return trim((string)$value);
}

define('SITE_NAME', 'АНО «Культура 75»');
define('SITE_TAGLINE', 'Культура, кино, искусство Забайкалья');
define('TELEGRAM_URL', 'https://t.me/zabkultura75');
define('VK_URL', 'https://vk.ru/zabkultura75');
define('MAX_URL', config_env('MAX_URL', 'https://max.ru/join/TQZ45uIOO8ibvaYo5Fu8isU47q5Y-gnCqkn5R1s5N0s'));

define('DIRECTOR_NAME', 'Чичёва Юлия Сергеевна');
define('CONTACT_EMAIL', 'julya_chicheva@mail.ru');
define('CONTACT_PHONE', '+79148047400');
define('CONTACT_ADDRESS', 'Забайкальский край, г. Чита, ул. Николая Островского, 56');

define('ADMIN_LOGIN', config_env('ADMIN_LOGIN', 'admin'));
define('ADMIN_PASSWORD_HASH', config_env('ADMIN_PASSWORD_HASH', '$2y$12$oROAwzzAp7tUN.dHxPlqbuHTuWNzhZJDBBs4L8x72g/S7ErD4JLqy'));

define('DATA_FILE', __DIR__ . '/../data/news.json');
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('UPLOAD_PUBLIC_PATH', '/uploads');
define('MAX_UPLOAD_BYTES', (config_env('VERCEL', '') !== '' || config_env('VERCEL_ENV', '') !== '') ? 4 * 1024 * 1024 : 8 * 1024 * 1024);

define('SUPABASE_URL', rtrim(config_env('SUPABASE_URL', ''), '/'));
define('SUPABASE_SERVICE_ROLE_KEY', config_env('SUPABASE_SERVICE_ROLE_KEY', ''));
define('SUPABASE_STORAGE_BUCKET', config_env('SUPABASE_STORAGE_BUCKET', 'news-images'));
?>
