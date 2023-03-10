<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Traits\VoyagerDataControllerStub;

class SetSpreadSheetTest extends TestCase
{

    use VoyagerDataControllerStub;

    /**
     * Emulate table name.
     *
     * @var string
     */
    const POSTS_TABLE_NAME = 'posts';

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
     * Emulate test get db column
     *
     * @return void
     */
    public function test_get_db_column (): void
    {
        $columns = $this->_getColumns(self::POSTS_TABLE_NAME);

        $this->assertIsArray($columns);
    }

    /**
     * Test set column numbers
     *
     * @return void
     */
    public function test_set_col_num (): void
    {
        $columns = $this->_getColumns(self::POSTS_TABLE_NAME);

        $callBack = function ( int $key, string $value ): string {
            $k = $key + 1;
            return "{$this->prefix}{$value}{$this->affix} = {$k};";
        };
        $colNumsArr = array_map( $callBack, array_keys($columns), $columns );
        $this->assertIsArray($colNumsArr);

        $content = $this->_getContent($colNumsArr);
        $this->assertIsString($content);
    }

    /**
     * Test set title maps
     *
     * @return void
     */
    public function test_set_title_maps (): void
    {
        $columns = $this->_getColumns(self::POSTS_TABLE_NAME);

        $callBack = function ( string $value ): string {
            return "{$this->prefix}{$value}{$this->affix} => '{$value}',";
        };
        $colNumsArr = array_map( $callBack, $columns );
        $this->assertIsArray($colNumsArr);

        $content = $this->_getContent($colNumsArr);
        $this->assertIsString($content);
    }

    /**
     * Test set field maps
     *
     * @return void
     */
    public function test_set_field_maps (): void
    {
        $columns = $this->_getColumns(self::POSTS_TABLE_NAME);

        $callBack = function ( string $value ): string {
            $k = "{$this->prefix}{$value}{$this->affix}";
            $listObj = '$list';
            $valueStr = '$value';
            $v = <<<EOT
    function ( {$listObj} ) {
        {$valueStr} = {$listObj}->{$value};
        if ( is_numeric( {$valueStr} ) ) return {$valueStr};
        return !empty({$valueStr}) ? {$valueStr} : '';
    }
EOT;
            return "{$k} => {$v},";
        };
        $colNumsArr = array_map( $callBack, $columns );
        $this->assertIsArray($colNumsArr);

        $content = $this->_getContent($colNumsArr);
        $this->assertIsString($content);
    }

}
