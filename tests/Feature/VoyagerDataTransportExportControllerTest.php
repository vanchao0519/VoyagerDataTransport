<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerGetControllerName;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataTransportExportControllerTest
 * @package Tests\Feature
 */
class VoyagerDataTransportExportControllerTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;
    use VoyagerGetControllerName;
    use VoyagerDataController;

    /**
     * Controller name pre.
     *
     * @var string
     */
    protected $_controllerNamePre = 'Export';

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
     * Test that generate export controller command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {
        $this->artisan("voyager:data:export:controller {$this->_getTableName()}");

        // Test that the Import Controller file is created.
        $this->assertFileExists($this->_getFile());
    }

    /**
     * Get the Export Controller file path.
     *
     * @return string
     */
    public function _getFile (): string
    {
        $fileName = $this->_getControllerName($this->_controllerNamePre ,$this->_getTableName());
        return "{$this->_filePath}{$fileName}{$this->_fileExt}";
    }
}
