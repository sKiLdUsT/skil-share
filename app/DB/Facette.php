<?php

namespace DB;

interface Facette {
    public function save();
    public function last_error();
}