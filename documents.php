<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Документы — ' . SITE_NAME;
$pageDescription = 'Устав и отчетность АНО «Культура 75».';
require_once __DIR__ . '/includes/header.php';
$officialDocs = [
  ['Устав АНО «Культура 75»', 'Устав автономной некоммерческой организации, утвержденный решением единственного учредителя от 14.01.2025.', '/assets/docs/ustav-ano-chita-2025.pdf'],
  ['Пояснение к отчетности за 2025 год', 'Пояснение к упрощенной бухгалтерской отчетности: сведения об организации, целях, поступлениях и выплатах за 2025 год.', '/assets/docs/poyasnenie-otchetnost-2025.pdf'],
];
?>
<section class="page-hero">
  <div class="container">
    <span class="eyebrow">Открытость</span>
    <h1>Документы</h1>
    <p>В этом разделе размещены основные документы АНО «Культура 75».</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="section-head news-head-clean">
      <div><span class="eyebrow eyebrow-dark">Официальные документы</span><h2 class="section-title">Устав и отчетность</h2></div>
      <a class="btn btn-outline-dark" href="/contacts.php">Связаться с АНО</a>
    </div>
    <div class="documents-grid">
      <?php foreach ($officialDocs as $doc): ?>
        <article class="card document-card">
          <div class="doc-icon">PDF</div>
          <h3><?= e($doc[0]) ?></h3>
          <p><?= e($doc[1]) ?></p>
          <a class="btn btn-dark" href="<?= e($doc[2]) ?>" target="_blank" rel="noopener">Открыть документ</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
