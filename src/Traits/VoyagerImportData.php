<?php

namespace VoyagerDataTransport\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use VoyagerDataTransport\Services\ChunkReadFilter;

/**
 * Trait VoyagerImportData
 * @package VoyagerDataTransport\Traits
 */
trait VoyagerImportData {

    /**
     * Redirect url
     *
     * @var string
     */
    private $_redirectUrl = '/admin';

    /**
     * Should skip header
     *
     * @var boolean
     */
    private $_shouldSkipHeader = true;

    /**
     * Input file name
     *
     * @var string
     */
    private $inputFileName = 'userfile';

    /**
     * Process excel file
     *
     * @param string $fileName
     * @param int $chunkSize This number is bigger, more memory used
     * @return array<string, bool|string>
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function processExcel (string $fileName, int $chunkSize = 5000): array
    {
        $fileName = Storage::disk('local')->path($fileName);
        $inputFileType = IOFactory::identify($fileName);
        $chunkFilter = $this->chunkReadFilter();
        /** @var \PhpOffice\PhpSpreadsheet\Reader\Xlsx | \PhpOffice\PhpSpreadsheet\Reader\Xls | \PhpOffice\PhpSpreadsheet\Reader\Csv **/
        $reader = IOFactory::createReader($inputFileType);

        $info = $reader->listWorksheetInfo($fileName);
        /** @var int **/
        $totalRows = $info[0]['totalRows'];

        $reader->setReadFilter($chunkFilter);
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);

        $result = [];
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
        return $result;
    }

    /**
     * Upload action
     *
     * @param Request $req
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function upload(Request $req)
    {
        $inputFileName = $this->inputFileName;

        $this->setRedirectUrl();

        $_shouldSkipHeader = $req->get('shouldSkipHeader');
        $_shouldSkipHeader = intval($_shouldSkipHeader);

        if (self::SKIP_HEADER !== $_shouldSkipHeader) $this->_shouldSkipHeader = false;

        if ($req->hasFile($inputFileName)) {

            /** @var \Illuminate\Http\UploadedFile **/
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
            $time = "$time";
            $filePath = 'uploads/' . $time;
            Storage::makeDirectory($filePath);

            /** @var string **/
            $hashName = hash('crc32', $time);

            /** @var string **/
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
     * @param int[] $data
     * @return array<string, bool | string>
     */
    protected function importData (array $data): array
    {
        //TODO: Add your logic to import data to database with DB or Eloquent
        return ['status' => false, 'message' => 'Unknown status'];
    }


    /**
     * Set and return IReadFilter
     *
     * @return ChunkReadFilter
     */
    protected function chunkReadFilter (): ChunkReadFilter
    {
        $object = new ChunkReadFilter();
        return $object;
    }


}

