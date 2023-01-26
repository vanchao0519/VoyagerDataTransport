<?php

namespace Tests\Unit;

class ConfigGeneratorTest extends \PHPUnit\Framework\TestCase {

    /**
     * Laravel route verbs name [get].
     *
     * @var string
     */
    const ROUTE_GET = 'get';

    /**
     * Laravel route verbs name [post].
     *
     * @var string
     */
    const ROUTE_POST = 'post';

    /**
     * Import controller permission pre.
     *
     * @var string
     */
    const PERMISSION_PRE_IMPORT = 'browse_import_';

    /**
     * Export controller permission pre.
     *
     * @var string
     */
    const PERMISSION_PRE_EXPORT = 'browse_export_';

    /**
     * Test data table name.
     *
     * @var string
     */
    private $_tableName = 'posts';

    /**
     * Import controller pre.
     *
     * @var string
     */
    private $_importPre = 'Import';

    /**
     * Export controller pre.
     *
     * @var string
     */
    private $_exportPre = 'Export';

    /**
     * Import url pre.
     *
     * @var string
     */
    private $_urlImportPre = '/import_';

    /**
     * Export url pre.
     *
     * @var string
     */
    private $_urlExportPre = '/export_';

    /**
     * Import alias pre.
     *
     * @var string
     */
    private $_aliasImportPre = 'voyager.browse_import_';

    /**
     * Export alias pre.
     *
     * @var string
     */
    private $_aliasExportPre = 'voyager.browse_export_';

    /**
     * Key url.
     *
     * @var string
     */
    private $_url = 'url';

    /**
     * Key controller name.
     *
     * @var string
     */
    private $_controllerName = 'controllerName';

    /**
     * Key action name.
     *
     * @var string
     */
    private $_actionName = 'actionName';

    /**
     * Key alias.
     *
     * @var string
     */
    private $_alias = 'alias';

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
            self::ROUTE_GET => $this->_getMapping($_tableName),
            self::ROUTE_POST => $this->_postMapping($_tableName),
        ];

        $_routeConfig = [];

        foreach ($_routeMappings as $_verb => $_mappings) {
            foreach ($_mappings as $_mKey => $_functions) {
                foreach ($_functions as $_fKey => $_function) {
                    $_routeConfig[$_verb][$_mKey][$_fKey] = $_function() ;
                }
            }
        }

        $this->assertIsArray($_routeConfig);

        $this->assertTrue( array_key_exists ('get', $_routeConfig) );
        $this->assertTrue( array_key_exists ('post', $_routeConfig) );

        $_contentKeys = [
            'url',
            'controllerName',
            'actionName',
            'alias',
        ];

        $_keyAndValueExist = function ( string $_key, array $_dataSet ): void {
            $_isValid = isset($_dataSet[$_key]) && !empty($_dataSet[$_key]);
            $this->assertTrue( $_isValid );
        };

        $_isValueString = function (string $_key, array $_dataSet): void {
            $this->assertIsString( $_dataSet[$_key] );
        };

        $_getTestResult = function (array $dataSet) use ($_contentKeys, $_keyAndValueExist, $_isValueString): void {

            foreach ($_contentKeys as $contentKey) {
                $_keyAndValueExist ( $contentKey, $dataSet );
                $_isValueString ( $contentKey, $dataSet );
            }

            $this->assertEquals( count ( $_contentKeys ), count ( $dataSet ) );
        };

        $_loopTest = function ( array $dataSet ) use ( $_getTestResult ): void {
            $counter = count( $dataSet );

            while ( $counter > 0 ) {
                $currentData = current($dataSet);
                $_getTestResult($currentData);
                next($dataSet);
                $counter--;
            }
            reset($dataSet);
        };

        $this->assertEquals(2, count ( $_routeConfig['get'] ) );
        $_loopTest($_routeConfig['get']);

        $this->assertEquals(1, count ( $_routeConfig['post'] ) );
        $_loopTest($_routeConfig['post']);
    }

    /**
     * Test route config set replace stub.
     *
     * @return void
     */
    public function test_set_replace_stub_route() {

        $_tableName = $this->_tableName;

        $_routeMappings = [
            'get' => $this->_getMapping($_tableName),
            'post' => $this->_postMapping($_tableName),
        ];

        $config = [];

        foreach ($_routeMappings as $_verb => $_mappings) {
            foreach ($_mappings as $_mKey => $_functions) {
                foreach ($_functions as $_fKey => $_function) {
                    $config[$_verb][$_mKey][$_fKey] = $_function() ;
                }
            }
        }

        $recordArr = array_merge($config['get'], $config['post']);

        $search = [];
        $replace = [];

        foreach ($recordArr as $key => $records) {
            foreach ($records as $pre => $record) {
                $search[] = $this->_getSearchValue($key + 1, "{$pre}_");
                $replace[] = $record;
            }
        }

        $this->assertIsArray($recordArr);
        $this->assertIsArray($search);
        $this->assertIsArray($replace);
        $this->assertEquals(count($search), count($replace));
    }

    /**
     * Test set get mapping.
     *
     * @return void
     */
    public function test_set_get_mapping (): void
    {
        $mappings = $this->_getMapping($this->_tableName);
        $this->assertIsArray($mappings);

        $keys = [
            $this->_url,
            $this->_controllerName,
            $this->_actionName,
            $this->_alias,
        ];

        $callBack = function ( array $setting ) use ($keys) : array {
            $array = [];
            foreach ($keys as $key) $array[$key] = $setting[$key]();
            return $array;
        };

        $content = array_map($callBack, $mappings);
        $this->assertIsArray($content);

        $callBack = function ( array $setting ) use ($keys) : string {
            $content = '';
            foreach ($keys as $key => $value) {
                $tableSignal = 0 === $key ? '' : "\t";
                $content .= "{$tableSignal}'{$value}' => '{$setting[$value]}'," . PHP_EOL;
            }
            $content = <<<EOT
[
    {$content}
],
EOT;
            return $content;
        };

        $content = array_map($callBack, $content);
        $this->assertIsArray($content);

        $contents = '';
        foreach ($content as $setting) {
            $contents .= $setting;
        };
        $contents = <<<EOT
[
    {$contents}
]
EOT;
        $this->assertIsString($contents);
    }

    /**
     * Get search key.
     *
     * @param  int  $key
     * @param  string  $pre
     * @return string
     */
    private function _getSearchValue (int $key, string $pre): string {
        return "{{ {$pre}{$key} }}";
    }

    /**
     * Create [GET] route config data stub.
     *
     * @param  string  $tableName
     * @return array< int, array<string, callable> >
     */
    private function _getMapping (string $tableName): array
    {
        $_mapping = [
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'actionName' => function () {
                    return 'index';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}";
                },
            ],
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlExportPre}{$tableName}";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_exportPre, $tableName);
                },
                'actionName' => function () {
                    return 'export';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_aliasExportPre}{$tableName}";
                },
            ],
        ];

        return $_mapping;
    }


    /**
     * Create [POST] route config data stub.
     *
     * @return array< int, array<string, callable> >
     */
    private function _postMapping (string $tableName): array
    {
        $_mapping  = [
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}/upload";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'actionName' => function () {
                    return 'upload';
                },
                'alias' => function () use ($tableName) {
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