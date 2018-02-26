<?php ob_start(); ?>
    <div class="dropbox valign-wrapper center-align">
        <h1>Drop here</h1>
    </div>
    <h4>Upload a file</h4>
<?php if(isset($flash_error)):?>
    <p class="red darken-2"><?= $flash_error ?></p>
<?php endif; ?>
    <div id="upload_progress" class="progress" style="margin:-.1em 0 0 0;display:none">
        <div class="determinate"></div>
    </div>
    <div class="row" style="width:40vw;">
        <form class="col s12" action="/upload" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn">
                        <span>File</span>
                        <input type="file" name="file">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
                <div class="col s12 m6 push-m3">
                    <button class="btn waves-effect waves-light grey darken-2" type="submit">
                        Upload
                        <i class="material-icons right">cloud_upload</i>
                    </button>
                </div>
            </div>
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        </form>
    </div>
<?php
include(__DIR__ . '/base.php');
