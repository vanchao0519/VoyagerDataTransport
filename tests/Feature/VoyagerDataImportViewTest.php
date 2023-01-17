<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataImportViewTest
 * @package Tests\Feature
 */
class VoyagerDataImportViewTest extends TestCase implements ICommandStatus
{
    use ParameterTrait;

    /**
     * Confirm that create browse view command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {

        $this->artisan("voyager:data:transport:import-data:view {$this->_getTableName()}")
            ->assertExitCode(self::ALL_PROCESS_SUCCESS_CODE);
    }

    /**
     * Confirm that import-data view file is created.
     *
     * @return void
     */
    public function test_is_file_created (): void
    {
        $this->assertFileExists($this->_getFile());
    }


    /**
     * Get the import-data view file path
     *
     * @return string
     */
    private function _getFile (): string
    {
        $file = "resources/views/vendor/voyager/{$this->_getTableName()}/import-data.blade.php";
        return $file;
    }
}
