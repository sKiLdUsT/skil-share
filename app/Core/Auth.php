<?php

namespace Core;

use DB\User;

class Auth
{
    public static function check() {
        return boolval(isset($_SESSION['auth']) ? $_SESSION['auth'] : false);
    }
    public static function user() {
        if (self::check()) {
            try
            {
                return new User(['id', $_SESSION['uid']]);
            } catch (\TypeError $exception)
            {
                session_destroy();
                header('Location: /');
                return;
            }
        }
        else return false;
    }
}
