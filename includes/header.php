<?php require_once __DIR__ . '/storage.php'; ?>
<!doctype html>
<html lang="ru">
<head>
  <meta name="google-site-verification" content="iQrKrSy3ldP2yyxffXL_saYS4Lk7u2lNNCR0T_3yHTI" />
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/logo-ano75-icon.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? SITE_NAME) ?></title>
  <meta name="description" content="<?= e($pageDescription ?? SITE_TAGLINE) ?>">
  <link rel="preload" href="/assets/css/style.css?v=23" as="style">
  <link rel="stylesheet" href="/assets/css/style.css?v=23">
  <meta charset="UTF-8">
  <title>Культура 75</title>
  <meta name="google-site-verification" content="google5b4b8936b53f228c.html" />
</head>
<body class="<?= e($bodyClass ?? '') ?>">
<a class="skip-link" href="#main">К содержанию</a>
<header class="site-header">
  <div class="container header-grid">
    <a class="brand" href="/index.php" aria-label="На главную">
      <img class="brand-logo" src="/assets/img/logo-ano75-icon.png" alt="АНО 75">
      <span class="brand-copy">
        <span class="brand-title">АНО «Культура 75»</span>
        <span class="brand-subtitle">культура • кино • искусство</span>
      </span>
    </a>
    <nav class="main-nav" aria-label="Основное меню">
      <a class="<?= active_class('index.php') ?>" href="/index.php">О нас</a>
      <a class="<?= active_class('directions.php') ?>" href="/directions.php">Направления</a>
      <a class="<?= active_class('news.php') ?>" href="/news.php">Новости</a>
      <a class="<?= active_class('partners.php') ?>" href="/partners.php">Партнерство</a>
      <a class="<?= active_class('documents.php') ?>" href="/documents.php">Документы</a>
      <a class="<?= active_class('contacts.php') ?>" href="/contacts.php">Контакты</a>
    </nav>
  </div>
</header>
<main id="main">
