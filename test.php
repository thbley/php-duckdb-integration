<?php

error_reporting(E_ALL);

require 'DuckDB.php';

$db = new DuckDB('/tmp/duck1.db', __DIR__ . '/libduckdb.so', __DIR__ . '/duckdb.h');

$db->query('CREATE TABLE IF NOT EXISTS test_table (i INTEGER, j INTEGER, k VARCHAR)');

$db->query("INSERT INTO test_table VALUES (3, 4, 'FOO'), (5, 6, 'BAR'), (7, NULL, 'BAZ')");

$result = $db->query('SELECT * FROM test_table');
print_r($result);
