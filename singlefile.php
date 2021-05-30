<?php

// Simple version without error handling and cleanup
//
// Usage: php -dffi.enable=1 singlefile.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$ffi = FFI::cdef(file_get_contents(__DIR__ . '/duckdb.h'), __DIR__ . '/libduckdb.so');

$db = $ffi->new('duckdb_database');
$con = $ffi->new('duckdb_connection');
$result = $ffi->new('duckdb_result');

$ffi->duckdb_open(null, FFI::addr($db));
$ffi->duckdb_connect($db, FFI::addr($con));

$query = 'select current_date;';
$ffi->duckdb_query($con, $query, FFI::addr($result));

$val = $ffi->duckdb_value_varchar(FFI::addr($result), 0, 0);
echo FFI::string($val);

FFI::free($val);
$ffi->duckdb_destroy_result(FFI::addr($result));

$ffi->duckdb_disconnect(FFI::addr($con));
FFI::free($con);

$ffi->duckdb_close(FFI::addr($db));
FFI::free($db);

unset($ffi);
