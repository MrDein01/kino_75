<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Направления работы — ' . SITE_NAME;
require_once __DIR__ . '/includes/header.php';
$directions = load_directions(false);
$active = $_GET['tab'] ?? 'culture';
$current = find_direction_by_slug($active, false);
if (!$current && $directions) {
    $current = $directions[0];
    $active = $current['slug'];
}
?>
<section class="page-hero page-hero-light">
  <div class="container">
    <span class="eyebrow eyebrow-dark">Вкладки по направлениям</span>
    <h1>Направления работы</h1>
    <p>Нажмите на категорию, чтобы открыть краткую справку и фотоматериалы по выбранному направлению.</p>
  </div>
</section>
<section class="section section-tabs">
  <div class="container">
    <nav class="tabs-nav" aria-label="Направления">
      <?php foreach ($directions as $dir): ?>
        <a class="<?= ($dir['slug'] ?? '') === $active ? 'is-active' : '' ?>" href="/directions.php?tab=<?= e($dir['slug']) ?>"><span><?= e($dir['num']) ?></span><?= e($dir['title']) ?></a>
      <?php endforeach; ?>
    </nav>
    <?php if ($current): ?>
    <article class="direction-detail">
      <div class="direction-detail-text">
        <span class="num big-num"><?= e($current['num']) ?></span>
        <h2><?= e($current['title']) ?></h2>
        <p class="lead"><?= e($current['lead']) ?></p>
        <ul class="feature-list">
          <?php foreach (($current['points'] ?? []) as $point): ?><li><?= e($point) ?></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="direction-gallery">
        <?php foreach (direction_images($current) as $idx => $img): ?>
          <figure><img src="<?= e(image_url($img['src'])) ?>" alt="<?= e($current['title']) ?> — фото <?= $idx + 1 ?>"><figcaption><?= e($img['caption'] ?: ('Материал ' . ($idx + 1))) ?></figcaption></figure>
        <?php endforeach; ?>
      </div>
    </article>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
