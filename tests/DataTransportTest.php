<?php

namespace VoyagerDataTransport\Test;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;

class DataTransportTest extends \PHPUnit\Framework\TestCase {

    use VoyagerDataController;

    private $_controllerNamePre = "Import";

    private $_tableName = "posts";

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

}