<?php

namespace VoyagerDataTransport\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

trait VoyagerExportData {

    private $spreadSheet;
    private $sheet;
    private $writer;
    private $writerType;

    public function export ()
    {
        $this->exportSet();

        $this->spreadSheet = new Spreadsheet();
        $this->sheet = $this->spreadSheet->getActiveSheet();
        $this->setSpreadSheet();
        $this->setWriterType();

        $hashName = hash('crc32', time());
        $fileName = "{$hashName}.{$this->writerType}";

        try {
            $this->setWriter($this->writerType);
            $this->writer->save($fileName);
            $content = file_get_contents($fileName);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        header("Content-Disposition: attachment; filename=$fileName");
        exit($content);
    }

    protected function exportSet()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);
    }

    protected function csvWriterConfig ()
    {
        $this->writer->setDelimiter(',');
        $this->writer->setEnclosure('"');
        $this->writer->setSheetIndex(0);
    }

    protected function xlsxWriterConfig ()
    {
    }

    protected function pdfWriterConfig ()
    {
        $this->writer->setPreCalculateFormulas(false);
    }

    protected function setWriter (string $type)
    {
        $type = strtolower($type);
        if ( 'csv' === $type ) {
            $this->writer = new Csv($this->spreadSheet);
            $this->csvWriterConfig();
            return true;
        }
        if ( 'xlsx' === $type ) {
            $this->writer = new Xlsx($this->spreadSheet);
            $this->xlsxWriterConfig();
            return true;
        }
        if ('pdf' === $type ) {
            $this->writer = new Mpdf($this->spreadSheet);
            $this->pdfWriterConfig();
            return true;
        }
        return false;
    }

    protected function setSpreadSheet ()
    {
        // Set Header
        $this->sheet->setCellValueByColumnAndRow(1, 1, 'title 01 here');
        $this->sheet->setCellValueByColumnAndRow(2, 1, 'title 02 here');

        // Set the Content response the Header
        $this->sheet->setCellValueByColumnAndRow(1, 2, 'content 01 here');
        $this->sheet->setCellValueByColumnAndRow(2, 2, 'content 02 here');
    }

    /*
     * allowed type: xlsx, csv, pdf
     */
    protected function setWriterType ()
    {
        $this->writerType = 'xlsx';
    }
}
