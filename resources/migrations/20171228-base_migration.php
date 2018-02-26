<?php

use DB\Handler;
use DB\Migration;

class base_migration implements Migration
{
    public function up(Handler $handle)
    {
        $handle->new('users', [
            'id INT NOT NULL AUTO_INCREMENT',
            'username VARCHAR(256) UNIQUE NOT NULL',
            'email VARCHAR(256) UNIQUE NOT NULL',
            'password VARCHAR(60) NOT NULL',
            'sharex_key VARCHAR(128) NOT NULL',
            'date INT(10) UNSIGNED NOT NULL',
            'PRIMARY KEY (id)'
        ]);

        $handle->new('files', [
            'id VARCHAR(8) NOT NULL',
            'uid INT NOT NULL',
            'name VARCHAR(256) NOT NULL',
            'store VARCHAR(32) NOT NULL',
            'type INT(1) NOT NULL',
            'date INT(10) UNSIGNED NOT NULL',
            'disabled BOOL DEFAULT false',
        ]);
    }

    public function down(Handler $handle)
    {
        $handle->drop('users');
        $handle->drop('files');
    }
}