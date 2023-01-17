<?php

namespace Tests\Feature;

use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataTransportPublishTest
 * @package Tests\Feature
 */
class VoyagerDataTransportPublishTest extends TestCase implements ICommandStatus
{

    /**
     * Confirm that config publish command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {
        $this->artisan("voyager:data:transport:publish:config")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    /**
     * Confirm config file existed.
     *
     * @return void
     */
    public function test_is_file_created (): void
    {
        $this->assertFileExists($this->_getRouteFile());
        $this->assertFileExists($this->_getPermissionFile());
    }

    /**
     * Get the route config file path
     *
     * @return string
     */
    private function _getRouteFile (): string
    {
        $file = "app/VoyagerDataTransport/config/route/config.php";
        return $file;
    }

    /**
     * Get the permission config file path
     *
     * @return string
     */
    private function _getPermissionFile (): string
    {
        $file = "app/VoyagerDataTransport/config/permissions/config.php";
        return $file;
    }

}
