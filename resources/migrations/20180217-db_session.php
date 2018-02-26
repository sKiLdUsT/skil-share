<?php

use DB\Handler;
use DB\Migration;

class db_session implements Migration
{
    public function up(Handler $handle)
    {
        $handle->new('sessions', [
            'id varchar(32) NOT NULL',
            'access int(10) unsigned',
            'data text',
            'PRIMARY KEY (id)'
        ]);
    }

    public function down(Handler $handle)
    {
        $handle->drop('sessions');
    }
}