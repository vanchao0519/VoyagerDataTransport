<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;
use VoyagerDataTransport\Contracts\ICommandStatus;

class VoyagerDataTransportImportControllerTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;
    use VoyagerDataController;

    /**
     * Controller name pre.
     *
     * @var string
     */
    protected $_controllerNamePre = 'Import';

    /**
     * Test that generate import controller command is worked.
     *
     * @return void
     */
    public function test_command()
    {
        $this->artisan("voyager:data:import:controller {$this->_getTableName()}")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    public function test_is_file_exist ()
    {
        $fileName = $this->getControllerName($this->_getTableName());
        $ext = ".php";
        $path = 'app/VoyagerDataTransport/Http/Controllers/';
        $file = "{$path}{$fileName}{$ext}";
        $this->assertFileExists($file);
    }
}
