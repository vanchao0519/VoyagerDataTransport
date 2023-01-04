<?php

namespace VoyagerDataTransport\Test;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;

class ConfigGeneratorTest extends \PHPUnit\Framework\TestCase {

    const ROUTE_GET = 'get';
    const ROUTE_POST = 'post';

    const PERMISSION_PRE_IMPORT = 'browse_import_';
    const PERMISSION_PRE_EXPORT = 'browse_export_';

    private $_tableName = 'posts';

    private $_importPre = 'Import';
    private $_exportPre = 'Export';

    private $_urlImportPre = '/import_';
    private $_urlExportPre = '/export_';

    private $_aliasImportPre = 'voyager.browse_import_';
    private $_aliasExportPre = 'voyager.browse_export_';

    /**
     * Test import and export permission config data set.
     *
     * @return void
     */
    public function test_set_permission_config_content(): void
    {
        $_tableName = $this->_tableName;

        $_func = function ($_pre, $_table) {
            return "{$_pre}{$_table}";
        };

        $_permissionPre = [
            self::PERMISSION_PRE_IMPORT,
            self::PERMISSION_PRE_EXPORT,
        ];

        foreach ($_permissionPre as $_pre) {
            $_permissionConfig[] = $_func($_pre, $_tableName);
        }

        $expectedCounter = count($_permissionPre);
        $actualCounter = count($_permissionConfig);

        $expectedConfig = [
            self::PERMISSION_PRE_IMPORT . $_tableName,
            self::PERMISSION_PRE_EXPORT . $_tableName,
        ];

        $this->assertIsArray($_permissionConfig);
        $this->assertEquals($expectedCounter, $actualCounter);
        $this->assertEquals($expectedConfig, $_permissionConfig);

    }

    /**
     * Test route config data set.
     *
     * @return void
     */
    public function test_set_route_config_content()
    {
        $_tableName = $this->_tableName;

        $_routeMappings = [
            self::ROUTE_GET => $this->_getMapping(),
            self::ROUTE_POST => $this->_postMapping(),
        ];

        $_routeConfig = [];

        foreach ($_routeMappings as $_verb => $_mappings) {
            foreach ($_mappings as $_mKey => $_functions) {
                foreach ($_functions as $_fKey => $_function) {
                    $_routeConfig[$_verb][$_mKey][$_fKey] = $_function($_tableName) ;
                }
            }
        }

        $expectedCounter = count($_routeMappings[self::ROUTE_GET]) + count($_routeMappings[self::ROUTE_POST]);
        $actualCounter = count($_routeConfig[self::ROUTE_GET]) + count($_routeConfig[self::ROUTE_POST]);

        $this->assertIsArray($_routeConfig);
        $this->assertEquals($this->_expectedRouteConfig(), $_routeConfig);
        $this->assertEquals($expectedCounter, $actualCounter);
    }

    /**
     * Create [GET] route config data stub.
     *
     * @return array
     */
    private function _getMapping (): array
    {
        $_mapping = [
            [
                'url' => function ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}";
                },
                'controllerName' => function ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'alias' => function ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}";
                },
            ],
            [
                'url' => function ($tableName) {
                    return "{$this->_urlExportPre}{$tableName}";
                },
                'controllerName' => function ($tableName) {
                    return $this->_controllerNameGenerate($this->_exportPre, $tableName);
                },
                'alias' => function ($tableName) {
                    return "{$this->_aliasExportPre}{$tableName}";
                },
            ],
        ];

        return $_mapping;
    }

    /**
     * Create [POST] route config data stub.
     *
     * @return array
     */
    private function _postMapping (): array
    {
        $_mapping  = [
            [
                'url' => function ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}/upload";
                },
                'controllerName' => function ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'alias' => function ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}.upload";
                },
            ],
        ];

        return $_mapping;
    }

    /**
     * Get controller name.
     *
     * @param  string  $_namePre    Added to data table name before
     * @param  string  $_tableName  This data table name
     * @return string
     */    
    private function _controllerNameGenerate (string $_namePre, string $_tableName): string 
    {
        $tableName = strtolower($_tableName);
        $tempArr = explode("_", $tableName);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return "{$_namePre}{$baseName}";
    }

    /**
     * Get expected route config.
     *
     * @return array
     */    
    private function _expectedRouteConfig (): array
    {
        $_getMapping = $this->_getMapping();
        $_postMapping = $this->_postMapping();

        $_tableName = $this->_tableName;

        $_getConfig = [];
        $_postConfig = [];

        foreach ($_getMapping as $_mKey => $_functions) {
            foreach ($_functions as $_fKey => $_function) {
                $_getConfig[$_mKey][$_fKey] = $_function($_tableName);
            }
        }

        foreach ($_postMapping as $_mKey => $_functions) {
            foreach ($_functions as $_fKey => $_function) {
                $_postConfig[$_mKey][$_fKey] = $_function($_tableName);
            }
        }

        $_config = [
            'get' => $_getConfig,
            'post' => $_postConfig,
        ];

        return $_config;
    }

}