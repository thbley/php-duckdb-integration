.open test.db
PRAGMA enable_profiling;
CREATE TABLE test1 AS SELECT * FROM read_csv_auto('test.csv.gz');
DESCRIBE test1;
