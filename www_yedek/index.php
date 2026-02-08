<?php require_once "autoload.php";?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= SITE_NAME ?> - <?= DESCRIPTION ?></title>

<meta name="description" content="<?= DESCRIPTION ?>">
<meta name="keywords" content="<?= KEYWORDS ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= CANONICAL ?>">

<!-- Open Graph -->
<meta property="og:site_name" content="<?= SITE_NAME ?>">
<meta property="og:title" content="<?= SITE_NAME ?> - <?= DESCRIPTION ?>">
<meta property="og:description" content="<?= OG_DESCRIPTION ?>">
<meta property="og:image" content="<?= OG_IMAGE ?>">
<meta property="og:url" content="<?= CANONICAL ?>">
<meta property="og:type" content="website">

  <!-- Critical CSS -->
  <style><?php 
  echo minify_css(file_get_contents(__DIR__ . "/public/css/dahili.css"));
  ?></style>

  <!-- Non-critical CSS -->
  <link rel="preload"
        href="/public/css/harici.css"
        as="style"
        onload="this.rel='stylesheet'">
  <noscript>
    <link rel="stylesheet" href="/public/css/harici.css">
  </noscript>

  <!-- JS -->
  <script src="/script.js" defer></script>
</head>

<body class="page black ">
    <?php require_once __DIR__ . "/part/header.php"; ?>
    <main id="main" class="" role="main">
        <?php require_once __DIR__ . "/part/main.php"; ?>
    </main>

<footer class="container" role="contentinfo">
    <?php require_once __DIR__ . "/part/footer.php"; ?>
</footer>

 
 
</body>
</html>
