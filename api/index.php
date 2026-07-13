<?php

$root = dirname(__DIR__);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rawurldecode($path);

/*
|--------------------------------------------------------------------------
| Отдача статических файлов
|--------------------------------------------------------------------------
*/

$static = realpath($root . $path);

if (
    $static &&
    str_starts_with($static, $root) &&
    is_file($static)
) {
    $ext = strtolower(pathinfo($static, PATHINFO_EXTENSION));

    // Все НЕ PHP-файлы отдаём напрямую
    if ($ext !== 'php') {

        $mime = [
            'html' => 'text/html; charset=UTF-8',
            'txt'  => 'text/plain; charset=UTF-8',
            'xml'  => 'application/xml',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'svg'  => 'image/svg+xml',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            'webp' => 'image/webp',
            'pdf'  => 'application/pdf'
        ];

        header('Content-Type: ' . ($mime[$ext] ?? 'application/octet-stream'));

        readfile($static);
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Маршруты PHP
|--------------------------------------------------------------------------
*/

$routes = [
    '/' => '/index.php',
    '/index.php' => '/index.php',

    '/directions.php' => '/directions.php',
    '/partners.php' => '/partners.php',
    '/contacts.php' => '/contacts.php',
    '/documents.php' => '/documents.php',

    '/news.php' => '/news.php',
    '/news_view.php' => '/news_view.php',

    '/admin' => '/admin/index.php',
    '/admin/' => '/admin/index.php',
    '/admin/index.php' => '/admin/index.php',
    '/admin/login.php' => '/admin/login.php',
    '/admin/logout.php' => '/admin/logout.php',
    '/admin/news_form.php' => '/admin/news_form.php',
    '/admin/delete.php' => '/admin/delete.php',
];

$target = $routes[$path] ?? null;

if ($target === null && str_ends_with($path, '.php')) {
    $target = $path;
}

if ($target !== null) {

    $file = realpath($root . $target);

    if (
        $file &&
        str_starts_with($file, $root) &&
        is_file($file)
    ) {

        $_SERVER['SCRIPT_NAME'] = $target;
        $_SERVER['PHP_SELF'] = $target;

        chdir(dirname($file));

        require $file;
        exit;
    }
}

http_response_code(404);
echo '404 — страница не найдена';