<title><?= $title ?> - <?= isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : 'skil-share' ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php if(isset($metaTags) && is_array($metaTags)):
    foreach($metaTags as $name=>$value):?>
<meta name="<?= $name ?>" content="<?= $value ?>">
<?php endforeach; endif; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?= $assets['css'] ?>"