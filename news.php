<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Новости — ' . SITE_NAME;
require_once __DIR__ . '/includes/header.php';
$year = trim($_GET['year'] ?? '');
$query = trim($_GET['q'] ?? '');
$items = load_news(false);
$items = array_values(array_filter($items, function($item) use ($year, $query) {
    $itemYear = substr($item['date'] ?? '', 0, 4);
    if ($year !== '' && $itemYear !== $year) return false;
    if ($query !== '') {
        $haystack = mb_strtolower(($item['title'] ?? '') . ' ' . ($item['short'] ?? '') . ' ' . ($item['full'] ?? '') . ' ' . ($item['category'] ?? ''), 'UTF-8');
        return mb_strpos($haystack, mb_strtolower($query, 'UTF-8')) !== false;
    }
    return ((int)$itemYear >= 2025);
}));
$years = available_years();
?>
<section class="section">
  <div class="container">
    <div class="section-head news-head-clean">
      <div>
        <h1 class="section-title">Новости и проекты</h1>
      </div>
    </div>
    <div class="news-toolbar">
      <form method="get" action="/news.php">
        <input class="input" type="search" name="q" value="<?= e($query) ?>" placeholder="Поиск по новостям" style="width:260px;">
        <select name="year" aria-label="Год">
          <option value="">Все годы</option>
          <?php foreach ($years as $y): ?>
            <option value="<?= e($y) ?>" <?= $year === $y ? 'selected' : '' ?>><?= e($y) ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-dark" type="submit">Показать</button>
      </form>
    </div>
    <?php if (!$items): ?>
      <div class="card"><h3>Новостей пока нет</h3><p>Пока нет опубликованных материалов за выбранный период.</p></div>
    <?php else: ?>
      <div class="news-grid">
        <?php foreach ($items as $item): ?>
          <article class="news-card">
            <img src="<?= e(image_url($item['image'] ?? '')) ?>" alt="<?= e($item['title']) ?>">
            <div class="news-card-body">
              <div class="meta"><span class="badge"><?= e($item['category'] ?? 'Новости') ?></span><span><?= e(format_date_ru($item['date'] ?? '')) ?></span></div>
              <h3><?= e($item['title']) ?></h3>
              <p><?= e($item['short']) ?></p>
              <?php $photoCount = count(news_images($item)); if ($photoCount > 1): ?><span class="photo-count">Фото: <?= $photoCount ?></span><?php endif; ?>
              <a class="read-more" href="/news_view.php?slug=<?= e($item['slug']) ?>">Читать полностью →</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
