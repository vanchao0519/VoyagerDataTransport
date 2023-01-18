<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataExportViewTest
 * @package Tests\Feature
 */
class VoyagerDataExportViewTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;

    /**
     * Confirm that create browse view command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {

        $this->artisan("voyager:data:transport:export-data:view {$this->_getTableName()}")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    /**
     * Confirm that export-data view file is created.
     *
     * @return void
     */
    public function test_is_file_created (): void
    {
        $this->assertFileExists($this->_getFile());
    }


    /**
     * Get the export-data view file path
     *
     * @return string
     */
    private function _getFile (): string
    {
        $file = "resources/views/vendor/voyager/{$this->_getTableName()}/export-data.blade.php";
        return $file;
    }
}
