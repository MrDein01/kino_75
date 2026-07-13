<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'О нас — ' . SITE_NAME;
$pageDescription = SITE_TAGLINE;
require_once __DIR__ . '/includes/header.php';
$latest = array_slice(load_news(false), 0, 3);
?>
<section class="hero hero-main">
  <div class="hero-cinema" aria-hidden="true"><span class="reel reel-one"></span><span class="film-line"></span></div>
  <div class="container hero-grid">
    <div class="hero-content">
      <span class="eyebrow">АНО «Культура 75»</span>
      <h1>Культура, кино, искусство Забайкалья</h1>
      <p>Поддерживаем культурные инициативы, развиваем креативные индустрии, проводим события и собираем публичную историю деятельности организации.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="/news.php">Смотреть новости <span aria-hidden="true">→</span></a>
        <a class="btn btn-ghost" href="/directions.php">Направления работы</a>
        <a class="btn btn-ghost" href="/documents.php">Файлы и документы</a>
        <a class="btn btn-social" href="<?= e(VK_URL) ?>" target="_blank" rel="noopener">VK</a>
        <a class="btn btn-social" href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener">Telegram</a>
        <?php if (MAX_URL): ?><a class="btn btn-social" href="<?= e(MAX_URL) ?>" target="_blank" rel="noopener">MAX</a><?php else: ?><span class="btn btn-social btn-disabled" title="Добавьте ссылку MAX в includes/config.php">MAX</span><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="section about-intro">
  <div class="container about-grid">
    <div>
      <span class="eyebrow eyebrow-dark">О нас</span>
      <h2 class="section-title">АНО 75</h2>
    </div>
    <div class="about-text">
      <p><strong>Автономная некоммерческая организация содействия развитию киноиндустрии, культуры и искусства в Забайкальском крае «Культура 75»</strong> создана для решения социальных задач в сфере культуры, искусства, киноиндустрии и креативных инициатив.</p>
      <p>Организация зарегистрирована 01.04.2025 и ведет деятельность в Забайкальском крае. На сайте собрана открытая история работы: направления, новости, фотоотчеты, партнеры, документы и результаты проектов.</p>
    </div>
  </div>
</section>

<section class="section section-soft public-info">
  <div class="container">
    <div class="section-head">
      <div><span class="eyebrow eyebrow-dark">Публичная справка</span><h2 class="section-title">Деятельность и открытость</h2></div>
      <p class="section-lead">Страница помогает быстро показать экспертам и партнерам, чем занимается АНО, какие документы подтверждают статус организации и с кем развивается сотрудничество.</p>
    </div>
    <div class="info-grid">
      <article class="card info-card"><span class="big-num">01</span><h3>Культура и искусство</h3><p>Поддержка культурных инициатив, популяризация современного искусства и проведение событий для жителей региона.</p></article>
      <article class="card info-card"><span class="big-num">02</span><h3>Киноиндустрия</h3><p>Кинопоказы, развитие регионального кино, образовательные и просветительские форматы для начинающих авторов.</p></article>
      <article class="card info-card"><span class="big-num">03</span><h3>Грантовые проекты</h3><p>Подготовка и реализация социальных, социокультурных, культурных и образовательных программ.</p></article>
    </div>
  </div>
</section>

<section class="section documents-preview">
  <div class="container">
    <div class="section-head news-head-clean">
      <div><span class="eyebrow eyebrow-dark">Файлы</span><h2 class="section-title">Устав и отчетность</h2></div>
      <a class="btn btn-outline-dark" href="/documents.php">Перейти к файлам</a>
    </div>
    <div class="documents-grid">
      <article class="card document-card"><div class="doc-icon">PDF</div><h3>Устав АНО «Культура 75»</h3><p>Основной документ организации: цели, виды деятельности, порядок управления и компетенции директора.</p><a class="btn btn-dark" href="/assets/docs/ustav-ano-chita-2025.pdf" target="_blank" rel="noopener">Открыть Устав</a></article>
      <article class="card document-card"><div class="doc-icon">PDF</div><h3>Пояснение к отчетности за 2025 год</h3><p>Краткая финансовая и организационная справка: сведения об АНО, целях, поступлениях и проектах 2025 года.</p><a class="btn btn-dark" href="/assets/docs/poyasnenie-otchetnost-2025.pdf" target="_blank" rel="noopener">Открыть отчетность</a></article>
    </div>
  </div>
</section>

<section class="section section-soft" id="directions">
  <div class="container">
    <div class="section-head news-head-clean">
      <div><span class="eyebrow eyebrow-dark">Выберите направление</span><h2 class="section-title">Направления работы</h2></div>
      <a class="btn btn-outline-dark" href="/directions.php">Открыть все направления</a>
    </div>
    <?php $homeDirections = array_slice(load_directions(false), 0, 6); ?>
    <div class="grid grid-3">
      <?php foreach ($homeDirections as $dir): ?>
        <a class="card direction-card" href="/directions.php?tab=<?= e($dir['slug']) ?>"><span class="num"><?= e($dir['num']) ?></span><h3><?= e($dir['title']) ?></h3><p><?= e(mb_substr($dir['lead'], 0, 130, 'UTF-8')) ?><?= mb_strlen($dir['lead'], 'UTF-8') > 130 ? '...' : '' ?></p></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section news-preview">
  <div class="container">
    <div class="section-head news-head-clean">
      <h2 class="section-title">Последние новости</h2>
      <a class="btn btn-outline-dark" href="/news.php">Все новости</a>
    </div>
    <div class="news-grid">
      <?php foreach ($latest as $item): ?>
        <article class="news-card">
          <img src="<?= e(image_url($item['image'] ?? '')) ?>" alt="<?= e($item['title']) ?>">
          <div class="news-card-body">
            <div class="meta"><span class="badge"><?= e($item['category'] ?? 'Новости') ?></span><span><?= e(format_date_ru($item['date'] ?? '')) ?></span></div>
            <h3><?= e($item['title']) ?></h3>
            <p><?= e($item['short']) ?></p>
            <a class="read-more" href="/news_view.php?slug=<?= e($item['slug']) ?>">Подробнее →</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
