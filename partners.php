<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Партнерство — ' . SITE_NAME;
$pageDescription = 'Партнеры АНО «Культура 75».';
require_once __DIR__ . '/includes/header.php';
$partners = [
  'Министерство культуры Забайкальского края',
  'Союз «Гильдия продюсеров и локейшен менеджеров подготовки кинообъектов»',
  'ГАУ «Региональный телевизионный канал «Забайкалье»',
  'ГАУ «Молодежный центр «Искра»',
  'МБУ «Центр молодежных инициатив»',
  'Региональный ресурсный центр «Навигаторы детства» Забайкальского края',
  'Союз кинематографистов Забайкальского края',
  'АНО «За жизнь-ДВ»',
];
?>
<section class="page-hero">
  <div class="container"><span class="eyebrow">Сотрудничество</span><h1>Партнерство</h1><p>Организации, с которыми сотрудничает АНО «Культура 75» в сфере культуры, кино, искусства, молодежных и образовательных инициатив.</p></div>
</section>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div><span class="eyebrow eyebrow-dark">Партнеры</span><h2 class="section-title">Наши партнеры</h2></div>
    </div>
    <div class="partners-grid partners-grid-wide partners-names-only">
      <?php foreach ($partners as $partner): ?>
        <article class="card partner-card partner-name-card">
          <h3><?= e($partner) ?></h3>
        </article>
      <?php endforeach; ?>
    </div>
    <div class="card partner-note"><h3>Открыты к сотрудничеству</h3><p>АНО «Культура 75» развивает партнерства с учреждениями культуры, образовательными и молодежными организациями, медиаплощадками, экспертами киноиндустрии, бизнесом и общественными инициативами.</p><a class="btn btn-dark" href="/contacts.php">Открыть контакты</a></div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
