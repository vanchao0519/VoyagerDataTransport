<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Contracts\IPermissionParameters;

class VoyagerDataTransportPermissionsTest extends TestCase implements IPermissionParameters, ICommandStatus
{

    use ParameterTrait;

    /**
     * Confirm that generate permission detail config command is worked.
     *
     * @return void
     */
    public function test_command()
    {
        $this->artisan("voyager:data:transport:permission:detail:config {$this->_getTableName()}")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    /**
     * After command execute then confirm config file exist.
     *
     * @return void
     */
    public function test_is_file_created ()
    {
        $this->assertFileExists($this->_getFile());
    }

    public function test_check_permission_is_valid ()
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

    private function _getFile (): string
    {
        $file = "app/VoyagerDataTransport/config/permissions/tables/{$this->_getTableName()}.php";
        return $file;
    }

    private function _getConfig (): array
    {
        $data = require $this->_getFile();
        return $data;
    }

}
