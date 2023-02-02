<?php

namespace VoyagerDataTransport\Services;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataControllerStub;

class ImportDataService
{
    use VoyagerDataControllerStub;

    /**
     * Value affix.
     *
     * @var string
     */
    private $affix = '_col';

    /**
     * Data table name.
     *
     * @var string
     */
    private $_tableName;

    /**
     * Constructor function
     *
     * @return void
     */
    public function __construct(string $tableName)
    {
        $this->_tableName = $tableName;
    }

    /**
     * Set const fields
     *
     * @return string
     */
    public function setConstFields (): string
    {
        $columns = $this->_getColumns();

        $callBack = function ( int $key, string $value ): string {
            $const_field = "{$value}{$this->affix}";
            $const_field = strtoupper($const_field);
            $left_variable = "const {$const_field}";
            $right_variable = "{$key}";
            $field = "{$left_variable} = {$right_variable};";
            return $field;
        };

        $colNumsArr = array_map( $callBack, array_keys($columns), $columns );

        return $this->_getContent($colNumsArr, "\t");
    }

    /**
     * Set insert maps
     *
     * @return string
     */
    public function setInsertMaps (): string
    {
        $columns = $this->_getColumns();

        $callBack = function ( string $value ): string {
            $left_variable = "{$value}";
            $const_filed = "{$value}{$this->affix}";
            $const_filed = strtoupper($const_filed);
            $data_arr_str = '$data';
            $right_variable = "{$data_arr_str}[self::$const_filed]";
            $field = "'{$left_variable}' => {$right_variable},";
            return $field;
        };

        $colNumsArr = array_map( $callBack, $columns );

        $tableSignal = str_repeat("\t", 7);

        return $this->_getContent($colNumsArr, $tableSignal);
    }

}