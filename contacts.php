<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Контакты — ' . SITE_NAME;
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero page-hero-light">
  <div class="container"><span class="eyebrow eyebrow-dark">Отдельное окно</span><h1>Контакты</h1><p>Руководитель, адрес и каналы связи АНО «Культура 75».</p></div>
</section>
<section class="section contacts-section">
  <div class="container contacts-grid">
    <article class="contact-card main-contact">
      <span class="eyebrow eyebrow-dark">Директор</span>
      <h2><?= e(DIRECTOR_NAME) ?></h2>
      <p>По вопросам партнерства, новостей, грантовых проектов и проведения мероприятий.</p>
      <div class="contact-actions">
        <a class="btn btn-dark" href="tel:<?= e(preg_replace('/\D+/', '', CONTACT_PHONE)) ?>"><?= e(CONTACT_PHONE) ?></a>
        <a class="btn btn-outline-dark" href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>
      </div>
    </article>
    <article class="contact-card">
      <h3>Адрес</h3>
      <p><?= e(CONTACT_ADDRESS) ?></p>
    </article>
    <article class="contact-card">
      <h3>Социальные сети</h3>
      <p><a href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener">Telegram: @zabkultura75</a><br><a href="<?= e(VK_URL) ?>" target="_blank" rel="noopener">ВКонтакте: vk.ru/zabkultura75</a><?php if (MAX_URL): ?><br><a href="<?= e(MAX_URL) ?>" target="_blank" rel="noopener">MAX</a><?php else: ?><br><span class="muted">MAX: ссылка будет добавлена после уточнения.</span><?php endif; ?></p>
    </article>
    <article class="contact-card">
      <h3>Реквизиты</h3>
      <p>АНО «Культура 75»<br>ИНН 7500026218, КПП 750001001, ОГРН 1257500001330.</p>
    </article>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
