<?php
require_once __DIR__ . '/../includes/storage.php';
require_admin();
$id = (int)($_GET['id'] ?? 0);
$item = find_news_by_id($id, true);
if (!$item) {
    http_response_code(404);
    exit('Новость не найдена');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    delete_news_by_id($id);
    redirect_to('/admin/');
}
$bodyClass = 'admin-body';
$pageTitle = 'Удалить новость — ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>
<div class="login-box admin-card">
  <h1 style="font-size:34px;">Удалить новость?</h1>
  <p><strong><?= e($item['title']) ?></strong></p>
  <p class="help">Действие нельзя отменить.</p>
  <form method="post" class="admin-actions">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <button class="btn btn-dark" type="submit">Удалить</button>
    <a class="btn btn-outline-dark" href="/admin/">Отмена</a>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
