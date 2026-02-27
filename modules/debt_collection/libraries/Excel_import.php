<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Excel_import
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Parse Excel file and return array of records
     * @param string $filePath
     * @return array
     */
    public function parse_excel($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("Excel file does not exist: " . $filePath);
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $header = array_shift($sheet);
        $records = [];

        foreach ($sheet as $row) {
            $record = [];
            foreach ($header as $key => $field) {
                $record[strtolower(trim($field))] = $row[$key];
            }
            $records[] = $record;
        }

        return $records;
    }
}
