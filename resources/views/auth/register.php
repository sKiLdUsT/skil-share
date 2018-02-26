<?php ob_start(); ?>
    <h4>Register</h4>
    <?php if(isset($flash_error)):?>
    <p class="red darken-2"><?= $flash_error ?></p>
    <?php endif; ?>
    <div class="row" style="width:40vw;">
        <form class="col s12" action="/register" method="post">
            <div class="row">
                <div class="input-field col s12 m6">
                    <input id="username" type="text" class="validate" name="username">
                    <label for="username">Username</label>
                </div>
                <div class="input-field col s12 m6">
                    <input id="email" type="email" class="validate" name="email">
                    <label for="email">Email</label>
                </div>
                <div class="input-field col s12 m6">
                    <input id="password" type="password" class="validate" name="password">
                    <label for="password">Password</label>
                </div>
                <div class="input-field col s12 m6">
                    <input id="password_confirm" type="password" class="validate">
                    <label for="password_confirm">Confirm Password</label>
                </div>
                <div class="input-field col s12 push-m2 center-align">
                    <div class="g-recaptcha" data-sitekey="6LdY8kYUAAAAAI7fROtjvi5bJrwgZjRxkNiKtMW6"></div>
                </div>
                <div class="col s12 m6 push-m3">
                    <button class="btn waves-effect waves-light grey darken-2" type="submit">
                        Register
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        </form>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </div>
<?php
include(__DIR__ . '/../base.php');
