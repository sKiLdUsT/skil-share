<?php

namespace DB;

interface Migration {
    public function up(Handler $handler);
    public function down(Handler $handler);
}