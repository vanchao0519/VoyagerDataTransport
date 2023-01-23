<?php

namespace Tests\Feature;

use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataPermission;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataTransportImportPermissionTest
 * @package Tests\Feature
 */
class VoyagerDataTransportImportPermissionTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;
    use VoyagerDataPermission;

    /**
     * Import permission pre.
     *
     * @var string
     */
    private $_keyPre = 'browse_import_';

    /**
     * Confirm that generate permission detail config command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {
        /** @var string **/
        $tableName = $this->_getTableName();
        $this->artisan("voyager:data:transport:import:permission {$tableName}");

        $this->assertTrue($this->isPermissionExist($this->_keyPre, $tableName));
    }

}
