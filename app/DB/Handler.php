<?php

namespace DB;

class Handler
{
    public static function create() {
        switch ($_ENV['DB_DRIVER']) {
            case 'mysql':
            default:
                return new Driver\Mysql();
            case 'sqlite':
                return new Driver\Sqlite();
        }
    }
}