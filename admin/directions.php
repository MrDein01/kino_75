<?php
require_once __DIR__ . '/../includes/storage.php';
require_admin();
$storageError = '';
try {
    $directions = load_directions(true);
} catch (Throwable $e) {
    $directions = [];
    $storageError = $e->getMessage();
}
$bodyClass = 'admin-body';
$pageTitle = 'Редактирование направлений — ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
  <div class="admin-top">
    <div>
      <h1 style="font-size:42px;">Направления работы</h1>
      <p class="help">Здесь можно менять название, краткую справку, пункты и 3 изображения для каждой вкладки направления.</p>
    </div>
    <div class="admin-actions">
      <a class="btn btn-outline-dark" href="/admin/">Новости</a>
      <a class="btn btn-outline-dark" href="/admin/logout.php">Выйти</a>
    </div>
  </div>
  <?php if (function_exists('storage_notice')): ?><div class="alert alert-soft"><?= e(storage_notice()) ?></div><?php endif; ?>
  <?php if ($storageError): ?><div class="alert"><?= e($storageError) ?></div><?php endif; ?>
  <div class="admin-card">
    <table class="admin-table">
      <thead><tr><th>№</th><th>Направление</th><th>Краткая справка</th><th>Действия</th></tr></thead>
      <tbody>
      <?php foreach ($directions as $dir): ?>
        <tr>
          <td><?= e($dir['num'] ?? '') ?></td>
          <td><strong><?= e($dir['title'] ?? '') ?></strong><br><span class="help">/directions.php?tab=<?= e($dir['slug'] ?? '') ?></span></td>
          <td><span class="help"><?= e(mb_substr($dir['lead'] ?? '', 0, 160, 'UTF-8')) ?><?= mb_strlen($dir['lead'] ?? '', 'UTF-8') > 160 ? '...' : '' ?></span></td>
          <td class="admin-actions">
            <a class="btn btn-outline-dark" href="/admin/direction_form.php?slug=<?= e($dir['slug']) ?>">Редактировать</a>
            <a class="btn btn-outline-dark" target="_blank" href="/directions.php?tab=<?= e($dir['slug']) ?>">Открыть</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
