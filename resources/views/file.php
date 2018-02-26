<?php ob_start(); ?>
    <ul class="side-nav grey darken-2 white-text z-depth-2 hide-on-med-and-down left-align" style="transform:translateX(0px);height:calc(100% - 64px);top:64px">
        <li class="center-align"><i class="material-icons center">info</i></li>
        <li><div class="divider"></div></li>
        <li><i class="material-icons">content_paste</i><?= $file->name ?></li>
        <li><i class="material-icons">extension</i><?= $mime ?></li>
        <li><i class="material-icons">sd_storage</i><?= $size ?>MB</li>
        <li><i class="material-icons">account_circle</i><?= $uploader ?></li>
        <?php switch($file->type):;case 1: ?>
            <li><div class="divider"></div></li>
            <li><i class="material-icons">aspect_ratio</i> <?= $info['video']['resolution_x'] . ' x ' . $info['video']['resolution_y'] ?></li>
            <li><i class="material-icons">invert_colors</i> <?= $info['video']['bits_per_sample'] ?>bit</li>
        <?php break;case 2: ?>
            <li><div class="divider"></div></li>
            <li><i class="material-icons">aspect_ratio</i> <?= $info['video']['resolution_x'] . ' x ' . $info['video']['resolution_y'] ?></li>
            <li><i class="material-icons">content_copy</i> <?= $info['video']['frame_rate'] ?> FPS</li>
            <li><i class="material-icons">hourglass_empty</i> <?= $info['playtime_string'] ?></li>
        <?php break;case 3: ?>
            <li><div class="divider"></div></li>
            <li><i class="material-icons">audiotrack</i> <?= (int)( $info['audio']['bitrate'] / 1024) ?>kbps <?= isset($info['audio']['bits_per_sample']) ? '(' . $info['audio']['bits_per_sample'] . 'bit)' : '' ?></li>
            <li><i class="material-icons">hourglass_empty</i> <?= $info['playtime_string'] ?></li>
            <li><div class="divider"></div></li>
            <?php if(isset($info['tags'])): ?>
            <?php if(isset($info['comments']) && isset($info['comments']['picture'])): ?>
            <li class="center-align"><img class="cover" src="data:<?= $info['comments']['picture'][0]['image_mime'] ?>;base64,<?= base64_encode($info['comments']['picture'][0]['data']) ?>"></li>
            <?php endif; ?>
            <li><i class="material-icons">audiotrack</i> <?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2']['title'][0] ?> </li>
            <li><i class="material-icons">recent_actors</i> <?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2']['artist'][0] ?> </li>
            <li><i class="material-icons">album</i> <?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2']['album'][0] ?> </li>
            <li><i class="material-icons">art_track</i> <?= $info['tags'][$mime === 'audio/x-flac' ? 'vorbiscomment' : 'id3v2'][$mime === 'audio/x-flac' ? 'tracknumber' : 'track_number'][0] ?> </li>
            <?php endif; ?>
        <?php break;case 0: ?>
            <li><div class="divider"></div></li>
            <li><i class="material-icons">assignment</i><?= str_replace(__DIR__ . '/../../storage/' . $file->store . ':', '', shell_exec('file ' . __DIR__ . '/../../storage/' . $file->store)) ?></li>
        <?php endswitch; ?>
        <li><div class="divider"></div></li>
        <li><a class="btn waves-effect waves-light grey darken-1 white-text" href="/<?= $file->id ?>?raw"><i class="material-icons left white-text">file_download</i>Download</a></li>
    </ul>
    <div class="grey darken-4 media">
        <?php switch($file->type):;case 1: ?>
            <img src="/<?= $file->id ?>?raw">
        <?php break;case 2: ?>
            <video controls>
                <source src="/<?= $file->id ?>?raw" type="<?= mime_content_type(__DIR__ . '/../../storage/' . $file->store) ?>">
                <p>Your browser does not support the video tag.</p>
            </video>
        <?php break;case 3: ?>
            <div class="audio-container valign-wrapper">
                <div id="wavesurfer" class="valign">
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <div class="background"></div>
            </div>
        <?php break;case 4: ?>
            <div class="text-container container valign-wrapper">
                <div class="valign">
                    <pre class="grey darken-2 left-align z-depth-2"><?php
                        $str = preg_split("/\\r\\n|\\r|\\n/", htmlentities(file_get_contents(__DIR__ . '/../../storage/' . $file->store)));
                        echo '<span>' . implode('</span><span>', $str) . '</span>'; ?></pre>
                </div>
            </div>
        <?php break;case 5: ?>
            <div class="reader-container"></div>
        <?php break;case 0: ?>
            <div class="container valign-wrapper" style="height:100%">
                <div class="valign" style="width:100%;">
                    <p><i class="material-icons large">content_copy</i></p>
                </div>
            </div>
        <?php endswitch; ?>
    </div>
<?php
include(__DIR__ . '/base.php');
