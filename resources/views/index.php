<?php ob_start(); ?>
<h4>Hello, <?= $user->username ?>!</h4>
    <div class="row">
<?php switch (\Core\Auth::check()):
    case true: $config = "{\n  \"Name\": \"" . ($_ENV['APP_NAME'] ?: 'skil-share') . "\",\n  \"DestinationType\": \"ImageUploader, TextUploader, FileUploader\",\n  \"RequestURL\": \"http" . ($_SERVER['HTTPS'] ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . "/upload\",\n  \"FileFormName\": \"file\",\n  \"Arguments\": {\n    \"key\": \"$user->sharex_key\"\n  },\n  \"ResponseType\": \"RedirectionURL\"\n}";?>
        <div class="col s12 m4 l4">
            <a class="btn waves-effect waves-light grey darken-1" href="/files"><i class="material-icons left">find_in_page</i>My Files</a>
        </div>
        <div class="col s12 m4 l4">
            <a class="btn waves-effect waves-light grey darken-1 modal-trigger" href="#config"><i class="material-icons left">settings_applications</i>Config</a>
            <div id="config" class="modal grey darken-3">
                <div class="modal-content">
                    <h4>ShareX Config</h4>
                    <p>Save this file and import it into ShareX</p>
                    <code><pre><?= $config ?></pre></code>
                    <a class="btn waves-effect waves-light grey darken-1"
                       download="<?= $_ENV['APP_NAME'] ?: 'skil-share' ?>.sharex.sxcu"
                       href = "data:application/json;base64,<?= base64_encode($config) ?>">
                        <i class="material-icons left">cloud_download</i>Save
                    </a>
                </div>
            </div>
        </div>
        <div class="col s12 m4 l4">
            <a class="btn waves-effect waves-light grey darken-1" href="/logout"><i class="material-icons left">person_outline</i>Logout</a>
        </div>
<?php break; case false: ?>
        <div class="col s12 m4 push-m2 l4 push-l2">
            <a class="btn waves-effect waves-light grey darken-1" href="/login"><i class="material-icons left">vpn_key</i>Login</a>
        </div>
        <div class="col s12 m4 push-m2 l4 push-l2">
            <a class="btn waves-effect waves-light grey darken-1" href="/register"><i class="material-icons left">person_add</i>Register</a>
        </div>
    </div>
<?php break; endswitch; ?>
<?php
include('base.php');
