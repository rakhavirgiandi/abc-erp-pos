<?php

namespace Database\Seeders;

class CsvtoArray
{
    public function csv_to_array($filename, $header)
    {
        $delimiter = ',';

        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $data = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {

                if (empty(array_filter($row))) {
                    continue;
                }

                if (count($row) > count($header)) {
                    $row = array_slice($row, 0, count($header));
                }

                if (count($row) < count($header)) {
                    continue;
                }

                $data[] = array_combine($header, $row);
            }

            fclose($handle);
        }

        return $data;
    }
}
