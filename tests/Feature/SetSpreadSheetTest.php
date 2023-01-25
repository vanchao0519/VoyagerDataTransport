<?php

namespace Tests\Feature;

use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Services\SetSpreadSheetService;

class SetSpreadSheetTest extends TestCase
{

    use ParameterTrait;

    /**
     * Test set column num.
     *
     * @return void
     */
    public function test_set_col_num (): void
    {
        $service = $this->_getService();
        $content = $service->setColNum();
        $this->assertIsString($content);
    }

    /**
     * Test set title maps.
     *
     * @return void
     */
    public function test_set_title_maps (): void
    {
        $service = $this->_getService();
        $content = $service->setTitleMaps();
        $this->assertIsString($content);
    }

    /**
     * Test set title maps.
     *
     * @return void
     */
    public function test_set_field_maps (): void
    {
        $service = $this->_getService();
        $content = $service->setFieldMaps();
        $this->assertIsString($content);
    }

    /**
     * Get service object.
     *
     * @return SetSpreadSheetService
     */
    private function _getService (): object
    {
        $service = new SetSpreadSheetService($this->_getTableName());
        return $service;
    }
}
