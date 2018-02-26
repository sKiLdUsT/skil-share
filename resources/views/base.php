<?php $content = ob_get_clean(); ?>
<html>
<head>
    <?php include(__DIR__ . '/include/head.php')?>
</head>
<body class="grey darken-3">
<div class="navbar-fixed">
    <nav role="navigation" class="grey darken-3 z-depth-1">
        <div class="nav-wrapper container">
            <a href="/" class="brand-logo"><b><?= $_ENV['APP_NAME'] ?: 'skil-share' ?></b></a>
            <?php include(__DIR__ . '/include/nav.php')?>
        </div>
    </nav>
</div>
<main class="center">
    <div class="valign-wrapper">
        <div class="grey darken-2 z-depth-3 white-text main-content valign">
            <?= $content ?>
        </div>
    </div>
</main>
<script type="text/javascript" src="<?= $assets['manifest'] ?>"></script>
<script type="text/javascript" src="<?= $assets['vendor'] ?>"></script>
<?php if(isset($assets['js'])): ?>
<script type="text/javascript" src="<?= $assets['js'] ?>"></script>
<?php endif; ?>
</body>
</html>