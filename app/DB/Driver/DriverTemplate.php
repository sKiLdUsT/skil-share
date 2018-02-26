<?php

namespace DB\Driver;

interface DriverTemplate {
    public function new(string $tablename, array $vars);
    public function drop(string $tablename);
    public function get(string $tablename);
    public function where(string $tablename, array $search);
    public function has(string $tablename, array $search);
    public function insert(string $tablename, array $vars);
    public function delete(string $tablename, array $search);
    public function update(string $tablename, array $vars, array $search);
    public function last_error();
}