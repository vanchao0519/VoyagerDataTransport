<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataPermission;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataTransportExportPermissionTest
 * @package Tests\Feature
 */
class VoyagerDataTransportExportPermissionTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;
    use VoyagerDataPermission;

    /**
     * Export permission pre.
     *
     * @var string
     */
    private $_keyPre = 'browse_export_';

    /**
     * Confirm that generate permission detail config command is worked.
     *
     * @return void
     */
    public function test_command (): void
    {
        /** @var string **/
        $tableName = $this->_getTableName();
        $this->artisan("voyager:data:transport:export:permission {$tableName}");

        $this->assertTrue($this->isPermissionExist($this->_keyPre, $tableName));
    }
}
