</main>
<footer class="site-footer" id="footer">
  <div class="container footer-grid footer-grid-compact">
    <div>
      <div class="footer-brand-wrap">
        <img class="footer-logo" src="/assets/img/logo-ano75-icon.png" alt="АНО Культура 75">
        <div class="footer-brand-copy">
          <div class="footer-brand-title">АНО «Культура 75»</div>
          <div class="footer-brand-subtitle">культура • кино • искусство</div>
        </div>
      </div>
      <p>Сайт АНО «Культура 75»: новости, проекты, партнерство и публичная отчетность о культурных инициативах Забайкалья.</p>
    </div>
    <div>
      <div class="footer-title">Навигация</div>
      <p><a href="/directions.php">Направления работы</a><br><a href="/news.php">Новости</a><br><a href="/partners.php">Партнерство</a><br><a href="/documents.php">Документы</a><br><a href="/contacts.php">Контакты</a></p>
    </div>
    <div>
      <div class="footer-title">Социальные сети</div>
      <p><a href="<?= e(TELEGRAM_URL) ?>" target="_blank" rel="noopener">Telegram</a><br><a href="<?= e(VK_URL) ?>" target="_blank" rel="noopener">ВКонтакте</a><?php if (MAX_URL): ?><br><a href="<?= e(MAX_URL) ?>" target="_blank" rel="noopener">MAX</a><?php endif; ?></p>
      <div class="footer-title footer-edit-title">Редактирование</div>
      <p><a class="footer-edit-link" href="/admin/">Войти в редактирование сайта</a></p>
    </div>
  </div>
  <div class="container footer-bottom">© АНО «Культура 75», 2025. Все права защищены.</div>
</footer>
<script src="/assets/js/site.js?v=23" defer></script>
</body>
</html>
