<?php

namespace VoyagerDataTransport\Services;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataControllerStub;

class SetSpreadSheetService
{

    use VoyagerDataControllerStub;

    /**
     * Value prefix.
     *
     * @var string
     */
    private $prefix = '$';

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
     * Set column numbers
     *
     * @return string
     */
    public function setColNum (): string
    {
        $columns = $this->_getColumns();

        $callBack = function ( int $key, string $value ): string {
            $k = $key + 1;
            return "{$this->prefix}{$value}{$this->affix} = {$k};";
        };
        $colNumsArr = array_map( $callBack, array_keys($columns), $columns );

        return $this->_getContent($colNumsArr, "\t\t");
    }

    /**
     * Set title maps
     *
     * @return string
     */
    public function setTitleMaps (): string
    {
        $columns = $this->_getColumns();

        $callBack = function ( string $value ): string {
            return "{$this->prefix}{$value}{$this->affix} => '{$value}',";
        };
        $colNumsArr = array_map( $callBack, $columns );

        return $this->_getContent($colNumsArr);
    }

    /**
     * Set field maps
     *
     * @return string
     */
    public function setFieldMaps (): string
    {
        $columns = $this->_getColumns();

        $callBack = function ( string $value ): string {
            $k = "{$this->prefix}{$value}{$this->affix}";
            $listObj = '$list';
            $valueStr = '$value';
            $v = <<<EOT
function ( {$listObj} ) {
                {$valueStr} = {$listObj}->{$value};
                if ( is_numeric( {$valueStr} ) ) return {$valueStr};
                return !empty( {$valueStr} ) ? {$valueStr} : '';
            }
EOT;
            return "{$k} => {$v},";
        };
        $colNumsArr = array_map( $callBack, $columns );

        return $this->_getContent($colNumsArr);
    }

}