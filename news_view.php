<?php
require_once __DIR__ . '/includes/storage.php';
$slug = trim($_GET['slug'] ?? '');
$item = find_news_by_slug($slug, false);
if (!$item) {
    http_response_code(404);
    $pageTitle = 'Новость не найдена — ' . SITE_NAME;
    require_once __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container"><div class="card"><h1 class="section-title">Новость не найдена</h1><p>Проверьте ссылку или вернитесь к списку новостей.</p><p><a class="btn btn-dark" href="/news.php">К новостям</a></p></div></div></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
$images = news_images($item);
$pageTitle = $item['title'] . ' — ' . SITE_NAME;
$pageDescription = $item['short'] ?? '';
require_once __DIR__ . '/includes/header.php';
?>
<article class="article">
  <div class="container">
    <header class="article-header">
      <div class="meta"><span class="badge"><?= e($item['category'] ?? 'Новости') ?></span><span><?= e(format_date_ru($item['date'] ?? '')) ?></span></div>
      <h1><?= e($item['title']) ?></h1>
      <p class="section-lead"><?= e($item['short'] ?? '') ?></p>
    </header>
    <div class="news-carousel" data-carousel data-interval="25000">
      <div class="carousel-track">
        <?php foreach ($images as $idx => $img): ?>
          <figure class="carousel-slide <?= $idx === 0 ? 'is-active' : '' ?>"><img src="<?= e(image_url($img)) ?>" alt="<?= e($item['title']) ?> — фото <?= $idx + 1 ?>"></figure>
        <?php endforeach; ?>
      </div>
      <?php if (count($images) > 1): ?>
        <button class="carousel-btn prev" type="button" data-carousel-prev aria-label="Предыдущее фото">‹</button>
        <button class="carousel-btn next" type="button" data-carousel-next aria-label="Следующее фото">›</button>
        <div class="carousel-dots"><?php foreach ($images as $idx => $img): ?><button class="<?= $idx === 0 ? 'is-active' : '' ?>" type="button" data-carousel-dot="<?= $idx ?>" aria-label="Фото <?= $idx + 1 ?>"></button><?php endforeach; ?></div>
      <?php endif; ?>
    </div>
    <div class="article-content"><?= markdown_light($item['full'] ?? '') ?></div>
    <p style="margin-top:24px;"><a class="btn btn-outline-dark" href="/news.php">← Все новости</a></p>
  </div>
</article>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
