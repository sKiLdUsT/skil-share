<title><?= $title ?> - <?= isset($_ENV['APP_NAME']) ? $_ENV['APP_NAME'] : 'skil-share' ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php if(isset($metaTags) && is_array($metaTags)):
    foreach($metaTags as $name=>$value):?>
<meta name="<?= $name ?>" content="<?= $value ?>">
<?php endforeach; endif; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?= $assets['css'] ?>"
<meta property="og:title" content="<?= $name ?>" />
<?php if (isset($file)): ?>
    <meta name="twitter:card" content="summary" />
    <?php switch($file->type):;case 1: ?>
        <meta property="og:type" content="image" />
        <meta property="og:image" content="<?= isset($_SERVER['HTTPS']) ? 'https:' : 'http:'?>//<?= $_SERVER['HTTP_HOST'] ?>/<?= $file->id ?>?raw" />
        <meta property="og:image:width" content="<?= $info['video']['resolution_x'] ?>" />
        <meta property="og:image:height" content="<?=$info['video']['resolution_y'] ?>" />
        <?php break;case 2: ?>
        <meta property="og:type" content="video.other" />
        <meta property="og:video" content="<?= isset($_SERVER['HTTPS']) ? 'https:' : 'http:'?>//<?= $_SERVER['HTTP_HOST'] ?>/<?= $file->id ?>?raw" />
        <meta property="og:video:width" content="<?= $info['video']['resolution_x'] ?>" />
        <meta property="og:video:height" content="<?=$info['video']['resolution_y'] ?>" />
        <?php break;case 3: ?>
        <meta property="og:type" content="audio" />
        <meta property="og:audio" content="<?= isset($_SERVER['HTTPS']) ? 'https:' : 'http:'?>//<?= $_SERVER['HTTP_HOST'] ?>/<?= $file->id ?>?raw" />
        <?php if(isset($info['tags'])): ?>
            <meta property="og:music:album" content="<?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2']['album'][0] ?>" />
            <meta property="og:music:musician" content="<?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2']['artist'][0] ?>" />
            <meta property="og:music:album:track" content="<?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2'][$mime === 'audio/x-flac' ? 'tracknumber' : 'track_number'][0] ?>" />
        <?php endif; ?>
        <?php break;case 0: ?>
        <li><div class="divider"></div></li>
        <li><i class="material-icons">assignment</i><?= str_replace(__DIR__ . '/../../storage/' . $file->store . ':', '', shell_exec('file ' . __DIR__ . '/../../storage/' . $file->store)) ?></li>
    <?php endswitch; ?>
<?php endif; ?>
