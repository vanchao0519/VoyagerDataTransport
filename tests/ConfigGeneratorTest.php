<?php

namespace VoyagerDataTransport\Test;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;

class ConfigGeneratorTest extends \PHPUnit\Framework\TestCase {

    const ROUTE_GET = 'get';
    const ROUTE_POST = 'post';

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
            'browse_import_',
            'browse_export_'
        ];

        foreach ($_permissionPre as $_pre) {
            $_permissionConfig[] = $_func($_pre, $_tableName);
        }

        $expected = count($_permissionPre);
        $actual = count($_permissionConfig);

        $this->assertIsArray($_permissionConfig);
        $this->assertEquals($expected, $actual);

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

        $expected = count($_routeMappings[self::ROUTE_GET]) + count($_routeMappings[self::ROUTE_POST]);
        $actual = count($_routeConfig[self::ROUTE_GET]) + count($_routeConfig[self::ROUTE_POST]);

        $this->assertIsArray($_routeConfig);
        $this->assertEquals($expected, $actual);
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

}