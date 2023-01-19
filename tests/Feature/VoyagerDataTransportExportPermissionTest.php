<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataTransportExportPermissionTest
 * @package Tests\Feature
 */
class VoyagerDataTransportExportPermissionTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;

    /**
     * Confirm that generate permission detail config command is worked.
     *
     * @return void
     */
    public function test_command (): void
    {
        $this->artisan("voyager:data:transport:export:permission {$this->_getTableName()}")
            ->assertExitCode( (int) self::ALL_PROCESS_SUCCESS_CODE );
    }
}
