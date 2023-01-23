<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Contracts\IPermissionParameters;

/**
 * Class VoyagerDataTransportPermissionsTest
 * @package Tests\Feature
 */
class VoyagerDataTransportPermissionsTest extends TestCase implements IPermissionParameters, ICommandStatus
{

    use ParameterTrait;

    /**
     * Confirm that generate permission detail config command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {
        $this->artisan("voyager:data:transport:permission:detail:config {$this->_getTableName()}");

        // After command execute then confirm config file exist.
        $this->assertFileExists($this->_getFile());

    }

    /**
     * Validate permission
     *
     * @return void
     */
    public function test_check_permission_is_valid (): void
    {
        $config = $this->_getConfig();
        $this->assertIsArray($config);
        $this->assertGreaterThan(0, count($config));

        $import_permission = self::PERMISSION_PRE_IMPORT . $this->_getTableName();
        $export_permission = self::PERMISSION_PRE_EXPORT . $this->_getTableName();

        $isImportPermission = in_array($import_permission, $config);
        $this->assertTrue($isImportPermission);

        $isExportPermission = in_array($export_permission, $config);
        $this->assertTrue($isExportPermission);
    }


    /**
     * Get the detail config file path
     *
     * @return string
     */
    private function _getFile (): string
    {
        $file = "app/VoyagerDataTransport/config/permissions/tables/{$this->_getTableName()}.php";
        return $file;
    }

    /**
     * Get the data from config file
     *
     * @return array< int, string >
     */
    private function _getConfig (): array
    {
        $data = require $this->_getFile();
        return $data;
    }

}
