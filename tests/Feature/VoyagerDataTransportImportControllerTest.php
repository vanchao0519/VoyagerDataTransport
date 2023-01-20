<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataTransportImportControllerTest
 * @package Tests\Feature
 */
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
     * Controller file path.
     *
     * @var string
     */
    protected $_filePath = 'app/VoyagerDataTransport/Http/Controllers/';

    /**
     * Controller file extension.
     *
     * @var string
     */
    protected $_fileExt = '.php';

    /**
     * Test that generate import controller command is worked.
     *
     * @return void
     */
    public function test_command (): void
    {
        $this->artisan("voyager:data:import:controller {$this->_getTableName()}")
            ->assertExitCode( (int) self::ALL_PROCESS_SUCCESS_CODE );
    }

    /**
     * Test that the Import Controller file is created.
     *
     * @return void
     */
    public function test_is_file_exist (): void
    {
        $fileName = $this->getControllerName($this->_getTableName());
        $file = "{$this->_filePath}{$fileName}{$this->_fileExt}";
        $this->assertFileExists($file);
    }
}
