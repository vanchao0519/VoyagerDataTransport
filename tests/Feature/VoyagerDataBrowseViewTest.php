<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Contracts\ICommandStatus;

/**
 * Class VoyagerDataBrowseViewTest
 * @package Tests\Feature
 */
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
        $this->artisan("voyager:data:transport:browse:view {$this->_getTableName()}");

        // Confirm that browse view file is created.
        $this->assertFileExists($this->_getFile());
    }

    /**
     * Get the browse file path
     *
     * @return string
     */
    private function _getFile (): string
    {
        $file = "resources/views/vendor/voyager/{$this->_getTableName()}/browse.blade.php";
        return $file;
    }
}
