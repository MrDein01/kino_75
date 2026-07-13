<?php
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rawurldecode($path);
$routes = [
    '/' => '/index.php',
    '/index.php' => '/index.php',
    '/directions.php' => '/directions.php',
    '/partners.php' => '/partners.php',
    '/contacts.php' => '/contacts.php',
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
if ($target === null && str_ends_with($path, '.php')) $target = $path;
if ($target !== null) {
    $file = realpath($root . $target);
    if ($file && str_starts_with($file, $root) && is_file($file)) {
        $_SERVER['SCRIPT_NAME'] = $target;
        $_SERVER['PHP_SELF'] = $target;
        chdir(dirname($file));
        require $file;
        exit;
    }
}
http_response_code(404);
echo '404 — страница не найдена';
