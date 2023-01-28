<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Services\ImportDataService;

class SetImportDataControllerStubTest extends TestCase
{

    use ParameterTrait;

    /**
     * Test set const fields.
     *
     * @return void
     */
    public function test_set_const_fields (): void
    {
        $service = $this->_getService();
        $content = $service->setConstFields();
        $this->assertIsString($content);
    }

    /**
     * Test set insert maps.
     *
     * @return void
     */
    public function test_set_insert_maps (): void
    {
        $service = $this->_getService();
        $content = $service->setInsertMaps();
        $this->assertIsString($content);
    }

    /**
     * Get the service
     *
     * @return ImportDataService
     */
    private function _getService (): object
    {
        $service = new ImportDataService($this->_getTableName());
        return $service;
    }

}
