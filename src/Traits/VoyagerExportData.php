<?php

namespace VoyagerDataTransport\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Trait VoyagerExportData
 * @package VoyagerDataTransport\Traits
 */
trait VoyagerExportData {

    private $spreadSheet;
    private $sheet;
    private $writer;
    private $writerType;


    /**
     * Download action
     *
     * @param Request $req
     * @return void
     */
    public function download (Request $req): void
    {
        $_exportType = (int) $req->get('export_type');

        $this->exportSet();

        $this->spreadSheet = new Spreadsheet();
        $this->sheet = $this->spreadSheet->getActiveSheet();
        $this->setSpreadSheet();
        $this->setWriterType($_exportType);

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


    /**
     * Pre-set the export config
     *
     * @return void
     */
    protected function exportSet(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);
    }

    /**
     * Pre-set the csv writer config
     *
     * @return void
     */
    protected function csvWriterConfig (): void
    {
        $this->writer->setDelimiter(',');
        $this->writer->setEnclosure('"');
        $this->writer->setSheetIndex(0);
    }

    /**
     * Pre-set the xlsx writer config
     *
     * @return void
     */
    protected function xlsxWriterConfig (): void
    {
    }

    /**
     * Pre-set the pdf writer config
     *
     * @return void
     */
    protected function pdfWriterConfig (): void
    {
        $this->writer->setPreCalculateFormulas(false);
    }

    /**
     * Set the type of writer which you want
     *
     * The boolean return just to stop next code snippet execute.
     *
     * @return bool
     */
    protected function setWriter (string $type): bool
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

    /**
     * Set spread sheet
     *
     * Here is just a sample. You should concrete your own logic code in the export controller
     * Check demo for more details:
     * https://github.com/vanchao0519/VoyagerDataTransportDemo/blob/main/app/VoyagerDataTransport/Http/Controllers/ExportPosts.php
     *
     * @param int $type
     * @return void
     */
    protected function setSpreadSheet (): void
    {
        // Set Header
        $this->sheet->setCellValueByColumnAndRow(1, 1, 'title 01 here');
        $this->sheet->setCellValueByColumnAndRow(2, 1, 'title 02 here');

        // Set the Content response the Header
        $this->sheet->setCellValueByColumnAndRow(1, 2, 'content 01 here');
        $this->sheet->setCellValueByColumnAndRow(2, 2, 'content 02 here');
    }


    /**
     * Set writer type
     *
     * @param int $type
     * @return void
     */
    protected function setWriterType(int $type = -1): void
    {
        switch ($type) {
            case self::XLSX_TYPE:
                $this->writerType = 'xlsx';
                break;
            case self::CSV_TYPE:
                $this->writerType = 'csv';
                break;
            case self::PDF_TYPE:
                $this->writerType = 'pdf';
                break;
            default:
                $this->writerType = 'xlsx';
                break;
        }
    }
}
