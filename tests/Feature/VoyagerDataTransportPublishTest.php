<?php

namespace Tests\Feature;

use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

class VoyagerDataTransportPublishTest extends TestCase implements ICommandStatus
{

    /**
     * Confirm that config publish command is worked.
     *
     * @return void
     */
    public function test_command()
    {
        $this->artisan("voyager:data:transport:publish:config")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    /**
     * After command execute then confirm config file exist.
     *
     * @return void
     */
    public function test_is_file_created ()
    {
        $this->assertFileExists($this->_getRouteFile());
        $this->assertFileExists($this->_getPermissionFile());
    }

    private function _getRouteFile (): string
    {
        $file = "app/VoyagerDataTransport/config/route/config.php";
        return $file;
    }

    private function _getPermissionFile (): string
    {
        $file = "app/VoyagerDataTransport/config/permissions/config.php";
        return $file;
    }

}
