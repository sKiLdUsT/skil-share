<?php ob_start(); ?>
    <h4>Login</h4>
<?php if(isset($flash_error)):?>
    <p class="red darken-2"><?= $flash_error ?></p>
<?php endif; ?>
<?php if($_ENV['APP_LOGIN_NOTICE'] !== ''):?>
    <p class="orange darken-2"><?= $_ENV['APP_LOGIN_NOTICE'] ?></p>
<?php endif; ?>
    <div class="row">
        <form class="col s12" action="/login" method="post">
            <div class="row">
                <div class="input-field col s12 m6">
                    <input id="email" type="email" class="validate" name="email">
                    <label for="email">Email</label>
                </div>
                <div class="input-field col s12 m6">
                    <input id="password" type="password" class="validate" name="password">
                    <label for="password">Password</label>
                </div>
                <div class="col s12">
                    <input type="checkbox" class="filled-in" id="remember_me" name="remember_me" />
                    <label for="remember_me">Remember me</label>
                </div>
                <div class="col s12 m6 push-m3">
                    <button class="btn waves-effect waves-light grey darken-2" type="submit">Login
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        </form>
    </div>
<?php
include(__DIR__ . '/../base.php');
