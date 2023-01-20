<?php

namespace Tests\Unit;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;

class DataTransportTest extends \PHPUnit\Framework\TestCase {

    use VoyagerDataController;

    /**
     * Controller name pre.
     *
     * @var string
     */
    private $_controllerNamePre = "Import";

    /**
     * Mock data table name.
     *
     * @var string
     */
    private $_tableName = "posts";

    /**
     * Controller file path.
     *
     * @var string
     */
    protected $_filePath = 'tests/MockFile/Controllers/';

    /**
     * Controller file extension.
     *
     * @var string
     */
    protected $_fileExt = '.php';

    private function _tableNameToControllerName (string $tableName): string
    {
        $tableName = strtolower($tableName);
        $tempArr = explode("_", $tableName);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return $baseName;
    }

    protected function getNameInput()
    {
        return $this->_tableName;
    }

    private function _expectedControllerName (): string
    {
        return "{$this->_controllerNamePre}{$this->_tableNameToControllerName($this->_tableName)}";
    }

    public function test_getControllerName ()
    {
        $expected = $this->_expectedControllerName();
        $actual = $this->getControllerName($this->_tableName);
        $this->assertEquals($expected, $actual);
    }

    public function test_isFileExists ()
    {
        $this->assertTrue($this->isFileExists($this->_tableName));
    }

}