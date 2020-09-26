<?php

class DuckDB
{
    private FFI $ffi;
    private FFI\CData $db;
    private FFI\CData $conn;

    public function __construct(?string $databasePath, string $libraryPath, string $headersPath)
    {
        $this->ffi = FFI::cdef(file_get_contents($headersPath), $libraryPath);

        $this->db = $this->ffi->new('duckdb_database');
        $this->conn = $this->ffi->new('duckdb_connection');

        $error = $this->ffi->duckdb_open($databasePath, FFI::addr($this->db));
        if ($error) {
            throw new Exception('error open: ' . $databasePath);
        }

        $error = $this->ffi->duckdb_connect($this->db, FFI::addr($this->conn));
        if ($error) {
            throw new Exception('error connect');
        }
    }

    public function __destruct()
    {
        $this->ffi->duckdb_disconnect(FFI::addr($this->conn));
        $this->ffi->duckdb_close(FFI::addr($this->db));
        FFI::free($this->conn);
        FFI::free($this->db);
        unset($this->ffi);
    }

    public function query(string $query): array
    {
        $result = $this->ffi->new('duckdb_result');

        $error = $this->ffi->duckdb_query($this->conn, $query, FFI::addr($result));
        if ($error) {
            $message = FFI::string($result->error_message);

            $this->ffi->duckdb_destroy_result(FFI::addr($result));

            throw new Exception($message);
        }

        $data = [];
        $columns = [];

        for ($col = 0; $col < $result->column_count; $col++) {
            $columns[] = FFI::string($result->columns[$col]->name);

            for ($row = 0; $row < $result->row_count; $row++) {
                $value = $this->ffi->duckdb_value_varchar(FFI::addr($result), $col, $row);

                $data[$row][$columns[$col]] = FFI::string($value);

                FFI::free($value);
            }
        }

        $this->ffi->duckdb_destroy_result(FFI::addr($result));

        return $data;
    }
}
