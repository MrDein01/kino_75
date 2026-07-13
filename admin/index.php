<?php
require_once __DIR__ . '/../includes/storage.php';
require_admin();
$storageError = '';
try {
    $items = load_news(true);
} catch (Throwable $e) {
    $items = [];
    $storageError = $e->getMessage();
}
$bodyClass = 'admin-body';
$pageTitle = 'Админка — ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
  <div class="admin-top">
    <div>
      <h1 style="font-size:42px;">Админка новостей</h1>
      <p class="help">Здесь можно добавлять новости с 2025 года: заголовок, краткое описание, дата, категория, основное фото, дополнительные фото и полное описание.</p>
    </div>
    <div class="admin-actions">
      <a class="btn btn-dark" href="/admin/news_form.php">+ Добавить новость</a>
      <a class="btn btn-outline-dark" href="/admin/directions.php">Редактировать направления</a>
      <a class="btn btn-outline-dark" href="/admin/logout.php">Выйти</a>
    </div>
  </div>
  <?php if (function_exists('storage_notice')): ?><div class="alert alert-soft"><?= e(storage_notice()) ?></div><?php endif; ?>
  <?php if ($storageError): ?><div class="alert"><?= e($storageError) ?></div><?php endif; ?>
  <div class="admin-card">
    <table class="admin-table">
      <thead><tr><th>Дата</th><th>Новость</th><th>Статус</th><th>Действия</th></tr></thead>
      <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= e($item['date'] ?? '') ?></td>
          <td><strong><?= e($item['title'] ?? '') ?></strong><br><span class="help"><?= e($item['short'] ?? '') ?></span></td>
          <td><?= !empty($item['published']) ? 'Опубликована' : 'Черновик' ?></td>
          <td class="admin-actions">
            <a class="btn btn-outline-dark" href="/admin/news_form.php?id=<?= (int)$item['id'] ?>">Редактировать</a>
            <?php if (!empty($item['published'])): ?><a class="btn btn-outline-dark" target="_blank" href="/news_view.php?slug=<?= e($item['slug']) ?>">Открыть</a><?php endif; ?>
            <a class="btn btn-dark" href="/admin/delete.php?id=<?= (int)$item['id'] ?>">Удалить</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
