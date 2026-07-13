<?php
require_once __DIR__ . '/../includes/storage.php';
app_session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $login = trim($_POST['login'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    if (hash_equals(ADMIN_LOGIN, $login) && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['is_admin'] = true;
        redirect_to('/admin/');
    }
    $error = 'Неверный логин или пароль.';
}
$bodyClass = 'admin-body';
$pageTitle = 'Вход в админку — ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>
<div class="login-box admin-card">
  <h1 style="font-size:34px;">Вход в админку</h1>
  <p class="help">Добавление новостей: тема, краткое описание, фото и полный текст.</p>
  <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <p><label>Логин<br><input class="input" name="login" autocomplete="username" required></label></p>
    <p><label>Пароль<br><input class="input" type="password" name="password" autocomplete="current-password" required></label></p>
    <button class="btn btn-dark" type="submit">Войти</button>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
