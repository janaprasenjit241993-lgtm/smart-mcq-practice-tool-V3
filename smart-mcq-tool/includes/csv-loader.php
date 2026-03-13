<?php

class SMPP_CSV_Loader {
    public function load($file_path) {
        $rows = [];

        if (!file_exists($file_path)) {
            return $rows;
        }

        if (($handle = fopen($file_path, 'r')) === false) {
            return $rows;
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return $rows;
        }

        $headers = array_map([$this, 'normalize_header'], $headers);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) !== count($headers)) {
                continue;
            }

            $row = array_combine($headers, $data);
            if (!$row) {
                continue;
            }

            $rows[] = array_map(static function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $row);
        }

        fclose($handle);

        return $rows;
    }

    private function normalize_header($header) {
        $header = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header);
        return strtolower(trim($header));
    }
}
