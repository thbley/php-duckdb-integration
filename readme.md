PHP example to integrate DuckDB using PHP-FFI
-----------------------------------------------

Currently there is no PHP extension available for using DuckDB, so I created a small library using PHP-FFI.

DuckDB is an embeddable SQL OLAP database management system.
It does not require external servers. Databases are stored in single files (similar to SQLite).
Compared to SQLite, DuckDB is much faster. E.g. I imported 16M rows from a CSV file in 5s on my notebook (i5-8250U).

DuckDB can import CSV files with automatic format detection and automatic table creation using:

    CREATE TABLE test1 AS SELECT * FROM read_csv_auto('test1.csv');
    CREATE TABLE test2 AS SELECT * FROM read_csv_auto('test2.csv.gz');

Usage:

    php -dffi.enable=1 test.php

    or:

    docker build -t php-ffi .
    docker run -it --rm -v $(pwd):/code php-ffi php /code/test.php

Requirements:

    PHP 7.4+ with FFI extension enabled

References:

- https://duckdb.org
- https://github.com/cwida/duckdb
- https://github.com/cwida/duckdb/releases/latest/download/libduckdb-linux-amd64.zip
- https://www.php.net/manual/en/book.ffi.php
