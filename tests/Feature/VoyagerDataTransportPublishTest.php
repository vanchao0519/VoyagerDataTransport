<?php

namespace Tests\Feature;

use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataRouteDetailConfig;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerGetControllerName;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Contracts\IRouteParameters;

/**
 * Class VoyagerDataTransportPublishTest
 * @package Tests\Feature
 */
class VoyagerDataTransportPublishTest extends TestCase implements ICommandStatus, IRouteParameters
{

    use ParameterTrait;
    use VoyagerGetControllerName;
    use VoyagerDataRouteDetailConfig;

    /**
     * Confirm that config publish command is worked.
     *
     * @return void
     */
    public function test_command(): void
    {
        $this->artisan("voyager:data:transport:publish:config");

        // Confirm config file existed.
        $this->assertFileExists($this->_getRouteFile());
        $this->assertFileExists($this->_getPermissionFile());

    }

    /**
     * Validate route config detail.
     *
     * @return void
     */
    public function test_validate_route_config (): void
    {
        $configs = require $this->_getRouteFile();
        $this->assertIsArray($configs);
        $this->assertGreaterThan(0, count($configs));

        $tableName = $this->_getTableName();

        $config = current($configs);
        $this->assertArrayHasKey('get', $config);
        $this->assertArrayHasKey('post', $config);

        $expected = count($this->_getMapping($tableName)) + count($this->_postMapping($tableName));
        $actual = 0;

        foreach ($config as $k => $v) {
            $actual += count($config[$k]);
        }

        $this->assertEquals($expected, $actual);
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
