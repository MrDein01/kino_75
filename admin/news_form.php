<?php
require_once __DIR__ . '/../includes/storage.php';
require_admin();
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$item = $id ? find_news_by_id($id, true) : null;
if ($id && !$item) { http_response_code(404); exit('Новость не найдена'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try { upsert_news_from_post($id); redirect_to('/admin/'); }
    catch (Throwable $e) { $error = $e->getMessage(); $item = array_merge($item ?? [], $_POST); }
}
$bodyClass = 'admin-body';
$pageTitle = ($id ? 'Редактировать новость' : 'Добавить новость') . ' — ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
  <div class="admin-top">
    <div><h1><?= $id ? 'Редактировать новость' : 'Добавить новость' ?></h1><p class="help">Можно выбрать основное фото и до двух дополнительных. Добавление новостей доступно только с 2025 года.</p></div>
    <a class="btn btn-outline-dark" href="/admin/">← Назад</a>
  </div>
  <?php if (function_exists('storage_notice')): ?><div class="alert alert-soft"><?= e(storage_notice()) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
  <form class="admin-card" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="form-grid">
      <p class="form-full"><label>Тема новости / заголовок<br><input class="input" name="title" required value="<?= e($item['title'] ?? '') ?>"></label></p>
      <p><label>Дата<br><input class="input" type="date" name="date" min="2025-01-01" required value="<?= e($item['date'] ?? date('Y-m-d')) ?>"></label></p>
      <p><label>Категория<br><input class="input" name="category" value="<?= e($item['category'] ?? 'Новости') ?>" placeholder="Кинопоказы, Проекты 2025, Мастер-классы"></label></p>
      <p class="form-full"><label>Краткое описание для карточки<br><textarea name="short" required style="min-height:110px;"><?= e($item['short'] ?? '') ?></textarea></label></p>
      <p class="form-full"><label>Полное описание<br><textarea name="full" required><?= e($item['full'] ?? '') ?></textarea></label><span class="help">Можно делать абзацы пустой строкой. Для списка начинайте строки с дефиса.</span></p>
      <div class="form-full photo-upload-grid">
        <p><label>Основное фото<br><input class="input" type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif"></label><?php if (!empty($item['image'])): ?><span class="help">Сейчас: <?= e($item['image']) ?></span><?php endif; ?></p>
        <p><label>Дополнительное фото 1<br><input class="input" type="file" name="image2" accept="image/jpeg,image/png,image/webp,image/gif"></label><?php if (!empty($item['image2'])): ?><span class="help">Сейчас: <?= e($item['image2']) ?></span><?php endif; ?></p>
        <p><label>Дополнительное фото 2<br><input class="input" type="file" name="image3" accept="image/jpeg,image/png,image/webp,image/gif"></label><?php if (!empty($item['image3'])): ?><span class="help">Сейчас: <?= e($item['image3']) ?></span><?php endif; ?></p>
      </div>
      <p class="form-full checkbox"><input type="checkbox" name="published" value="1" <?= (!isset($item['published']) || !empty($item['published'])) ? 'checked' : '' ?>> Опубликовать на сайте</p>
    </div>
    <div class="admin-actions" style="margin-top:18px;"><button class="btn btn-dark" type="submit">Сохранить</button><a class="btn btn-outline-dark" href="/admin/">Отмена</a></div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
