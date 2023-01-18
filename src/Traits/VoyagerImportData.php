<?php

namespace VoyagerDataTransport\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Trait VoyagerImportData
 * @package VoyagerDataTransport\Traits
 */
trait VoyagerImportData {

    private $_redirectUrl = '/admin';

    private $_shouldSkipHeader = true;

    private $inputFileName = 'userfile';

    /**
     * @param string $fileName
     * @param int $chunkSize This number is bigger, more memory used
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function processExcel (string $fileName, int $chunkSize = 5000)
    {
        $fileName = Storage::disk('local')->path($fileName);
        $inputFileType = IOFactory::identify($fileName);
        $chunkFilter = $this->chunkReadFilter();
        $reader = IOFactory::createReader($inputFileType);

        $info = $reader->listWorksheetInfo($fileName);
        $totalRows = $info[0]['totalRows'];

        $reader->setReadFilter($chunkFilter);
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);

        for ($startRow = 1; $startRow <= $totalRows; $startRow += $chunkSize) {
            $chunkFilter->setRows($startRow, $chunkSize);
            $spreadsheet = $reader->load($fileName);
            $tempData = $spreadsheet->getActiveSheet()->toArray();

            if ( $this->_shouldSkipHeader ) array_shift($tempData);

            if (!empty($tempData)  && $startRow < $totalRows ) {
                foreach ($tempData as $key => $detail) {
                    if(empty(array_filter($detail))) continue;
                    $result = $this->importData($detail);
                    if (false === $result['status']) {
                        return $result;
                    }
                }
            }
        }

    }


    /**
     * @param Request $req
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function upload(Request $req)
    {
        $inputFileName = $this->inputFileName;

        $this->setRedirectUrl();

        $_shouldSkipHeader = (int) $req->get('shouldSkipHeader');

        if (10 !== $_shouldSkipHeader) $this->_shouldSkipHeader = false;

        if ($req->hasFile($inputFileName)) {

            $file = $req->file($inputFileName);
            $ext = $file->getClientOriginalExtension();

            $validator = Validator::make([
                'extension' => $ext,
            ], [
                'extension' => 'in:xls,xlsx,csv',
            ]);

            if ( $validator->fails() ) {
                $message = $validator->errors()->all();
                return redirect()->back()->with(['message' => implode('<br/>', $message), 'message_type' => 'warning']);
            }

            $time = time();
            $filePath = 'uploads/' . $time;
            Storage::makeDirectory($filePath);

            $hashName = hash('crc32', $time);

            $fileName = $hashName . '.' . $ext;
            if ( Storage::putFileAs( $filePath, $file, $fileName ) ) {

                $urlFileName = $filePath . '/' . $fileName;

                $result = $this->processExcel($urlFileName);

                if (false === $result['status']) {
                    return redirect()->back()->with(['message' => $result['message']]);
                }

                return redirect($this->_redirectUrl)->with(['message' => 'Data import success']);
            }
        }

        return redirect()->back()->with(['message' => 'Redirect message 401']);
    }


    /**
     * Set redirect url
     *
     * @return void
     */
    protected function setRedirectUrl(): void
    {
        // reset $_redirectUrl
    }

    /**
     * Import data to database from csv or xlsx file
     *
     * Check demo for more details:
     * https://github.com/vanchao0519/VoyagerDataTransportDemo/blob/main/app/VoyagerDataTransport/Http/Controllers/ImportPosts.php
     *
     * @param array $data
     * @return array
     */
    protected function importData (array $data): array
    {
        //TODO: Add your logic to import data to database with DB or Eloquent
    }


    /**
     * Set and return IReadFilter
     *
     * @return IReadFilter
     */
    protected function chunkReadFilter (): IReadFilter {
        return new class implements IReadFilter {
            private $startRow = 0;
            private $endRow = 0;

            public function setRows (int $startRow, int $chunkSize): void {
                $this->startRow = $startRow;
                $this->endRow = $startRow + $chunkSize;
            }

            public function readCell ($columnAddress, $row, $worksheetName = ''): bool
            {
                if (($row === 1) || ($row >= $this->startRow && $row < $this->endRow)) {
                    return true;
                }
                return false;
            }
        };
    }


}

