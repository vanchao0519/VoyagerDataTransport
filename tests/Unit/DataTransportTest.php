<?php

namespace Tests\Unit;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;

/**
 * Class DataTransportTest
 * @package Tests\Unit
 */
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
     */ private $_tableName = "posts";

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

    /**
     * Convert table-name to controoler-name
     *
     * @return string
     */
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

    /**
     * Get table name
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return $this->_tableName;
    }

    /**
     * Generate result for test case
     *
     * @return string
     */
    private function _expectedControllerName (): string
    {
        return "{$this->_controllerNamePre}{$this->_tableNameToControllerName($this->_tableName)}";
    }

    /**
     * Test getControllerName function
     *
     * @return void
     */
    public function test_getControllerName (): void
    {
        $expected = $this->_expectedControllerName();
        $actual = $this->getControllerName($this->_tableName);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test file exists
     *
     * @return void
     */
    public function test_isFileExists (): void
    {
        $this->assertTrue($this->isFileExists($this->_tableName));
    }

}