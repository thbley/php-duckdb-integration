<?php

error_reporting(E_ALL);

$fp = fopen('test.csv', 'w');
fputcsv($fp, ['customer_id', 'product_id']);

for ($i = 0; $i < 50_000_000; $i++) {
    fputcsv($fp, [rand(100000, 1000000), rand(1000, 10000)]);
}

fclose($fp);
