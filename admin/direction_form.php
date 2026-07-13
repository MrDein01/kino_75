<?php
require_once __DIR__ . '/../includes/storage.php';
require_admin();
$slug = trim($_GET['slug'] ?? '');
$item = $slug ? find_direction_by_slug($slug, true) : null;
if (!$slug || !$item) { http_response_code(404); exit('Направление не найдено'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try { upsert_direction_from_post($slug); redirect_to('/admin/directions.php'); }
    catch (Throwable $e) { $error = $e->getMessage(); $item = array_merge($item ?? [], $_POST); $item['points'] = points_text_to_array($_POST['points'] ?? ($item['points'] ?? [])); }
}
$bodyClass = 'admin-body';
$pageTitle = 'Редактировать направление — ' . SITE_NAME;
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
  <div class="admin-top">
    <div>
      <h1><?= e($item['title']) ?></h1>
      <p class="help">Редактируйте текст вкладки направления и прикрепляйте 2–3 фотографии или иллюстрации.</p>
    </div>
    <a class="btn btn-outline-dark" href="/admin/directions.php">← Назад</a>
  </div>
  <?php if (function_exists('storage_notice')): ?><div class="alert alert-soft"><?= e(storage_notice()) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
  <form class="admin-card" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="form-grid">
      <p><label>Номер<br><input class="input" name="num" value="<?= e($item['num'] ?? '') ?>" placeholder="01"></label></p>
      <p><label>Системное имя<br><input class="input" value="<?= e($item['slug'] ?? '') ?>" disabled></label><span class="help">Используется в ссылке, не редактируется.</span></p>
      <p class="form-full"><label>Название направления<br><input class="input" name="title" required value="<?= e($item['title'] ?? '') ?>"></label></p>
      <p class="form-full"><label>Краткая справка<br><textarea name="lead" required style="min-height:130px;"><?= e($item['lead'] ?? '') ?></textarea></label></p>
      <p class="form-full"><label>Пункты списка<br><textarea name="points" style="min-height:160px;"><?= e(points_array_to_text($item['points'] ?? [])) ?></textarea></label><span class="help">Каждый пункт пишите с новой строки.</span></p>
      <div class="form-full photo-upload-grid">
        <?php for ($i = 1; $i <= 3; $i++): ?>
          <div>
            <p><label>Фото <?= $i ?><br><input class="input" type="file" name="image<?= $i ?>" accept="image/jpeg,image/png,image/webp,image/gif"></label><?php if (!empty($item['image' . $i])): ?><span class="help">Сейчас: <?= e($item['image' . $i]) ?></span><?php endif; ?></p>
            <p><label>Подпись <?= $i ?><br><input class="input" name="caption<?= $i ?>" value="<?= e($item['caption' . $i] ?? ('Материал ' . $i)) ?>"></label></p>
          </div>
        <?php endfor; ?>
      </div>
    </div>
    <div class="admin-actions" style="margin-top:18px;"><button class="btn btn-dark" type="submit">Сохранить направление</button><a class="btn btn-outline-dark" href="/admin/directions.php">Отмена</a></div>
  </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
