<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Traits\VoyagerImportData;

class MockImportDataTest extends TestCase
{

    use VoyagerImportData;
    use ParameterTrait;

    const SKIP_HEADER = 10;

    const TITLE_COL = 0;
    const EXCERPT_COL = 1;
    const BODY_COL = 2;
    const IMAGE_COL = 3;
    const SLUG_COL = 4;
    const META_DESCRIPTION_COL = 5;
    const META_KEYWORDS_COL = 6;
    const STATUS_COL = 7;

    /**
     * Test import data.
     *
     * @return void
     */
    public function test_import_data(): void
    {

        /*
         * Test data import to specific test data table
         */
        $tableName = $this->_getTableName();

        $beforeInsertCount = $count = DB::table($tableName)->count();

        $file = public_path() . "/data.csv";

        $result = $this->processExcel($file);
        $this->assertIsArray($result);
        $this->assertArrayHasKey("status", $result);
        $this->assertArrayHasKey("message", $result);

        $afterInsertCount = $count = DB::table($tableName)->count();

        $this->assertGreaterThan($beforeInsertCount, $afterInsertCount);

        /*
         * Reset specific test data table
         */
        $resetNum = $beforeInsertCount + 1;
        $deleteNum = $afterInsertCount - $beforeInsertCount;
        $deleteResult = DB::table($tableName)->where('id', '>', 0)->orderBy('id', 'desc')->limit($deleteNum)->delete();
        $this->assertEquals($deleteNum, $deleteResult);
        $resetResult = DB::statement('ALTER TABLE `'. $tableName . '` AUTO_INCREMENT='. $resetNum);
        $this->assertTrue($resetResult);
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
        try {
            DB::transaction(
                function () use ($data) {
                    $tableName = $this->_getTableName();
                    DB::table($tableName)
                        ->insert([
                            'title' => $data[self::TITLE_COL],
                            'excerpt' => $data[self::EXCERPT_COL],
                            'body' => $data[self::BODY_COL],
                            'image' => $data[self::IMAGE_COL],
                            'slug' => $data[self::SLUG_COL],
                            'meta_description' => $data[self::META_DESCRIPTION_COL],
                            'meta_keywords' => $data[self::META_KEYWORDS_COL],
                            'status' => $data[self::STATUS_COL],
                            'author_id' => 0,
                            'featured' => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            );
            return ['status' => true, 'message' => 'data insert success'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => "{$e->getMessage()}"];
        }
    }

    /**
     * Process excel file
     *
     * @param string $fileName
     * @param int $chunkSize This number is bigger, more memory used
     * @return array<string, bool|string>
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function processExcel (string $fileName, int $chunkSize = 5000): array
    {
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


}
