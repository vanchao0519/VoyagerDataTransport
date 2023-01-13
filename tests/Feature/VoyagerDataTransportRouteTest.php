<?php

namespace Tests\Feature;

use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Config\VoyagerDataTransportRoute;
use VoyagerDataTransport\Contracts\IRouteParameters;

class VoyagerDataTransportRouteTest extends TestCase implements IRouteParameters
{

    use ParameterTrait;

    /**
     * Confirm that generate route detail config command is worked.
     *
     * @return void
     */
    public function test_command()
    {
        $this->artisan("voyager:data:transport:route:detail:config {$this->_getTableName()}")
            ->assertExitCode(VoyagerDataTransportRoute::SUCCESS);
    }

    /**
     * After command execute then confirm config file exist.
     *
     * @return void
     */
    public function test_is_file_created ()
    {
        $this->assertFileExists($this->_getFile());
    }

    public function test_check_verb_key_exists ()
    {
        $config = $this->_getConfig();

        $this->assertArrayHasKey(self::GET, $config);
        $this->assertArrayHasKey(self::POST, $config);
    }

    public function test_check_parameters_exists ()
    {
        $config = $this->_getConfig();

        $checkArrayValidate = function ($configs) {
            foreach ($configs as $_config) {
                $this->assertArrayHasKey(self::URL, $_config);
                $this->assertArrayHasKey(self::CONTROLLER, $_config);
                $this->assertArrayHasKey(self::ACTION, $_config);
                $this->assertArrayHasKey(self::ALIAS, $_config);
                $this->assertIsString($_config[self::URL]);
                $this->assertIsString($_config[self::CONTROLLER]);
                $this->assertIsString($_config[self::ACTION]);
                $this->assertIsString($_config[self::ALIAS]);
            }
        };

        $checkArrayValidate($config[self::GET]);
        $checkArrayValidate($config[self::POST]);
    }

    private function _getFile (): string
    {
        $file = "app/VoyagerDataTransport/config/route/tables/{$this->_getTableName()}.php";
        return $file;
    }

    private function _getConfig (): array
    {
        $data = require $this->_getFile();
        return $data;
    }
}
