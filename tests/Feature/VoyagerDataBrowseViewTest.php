<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

class VoyagerDataBrowseViewTest extends TestCase implements ICommandStatus
{

    use ParameterTrait;

    /**
     * Confirm that create browse view command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {

        $this->artisan("voyager:data:transport:browse:view {$this->_getTableName()}")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    public function test_is_file_created ()
    {
        $this->assertFileExists($this->_getFile());
    }

    private function _getFile (): string
    {
        $file = "resources/views/vendor/voyager/{$this->_getTableName()}/browse.blade.php";
        return $file;
    }
}