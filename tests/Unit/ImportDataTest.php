<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Traits\VoyagerDataControllerStub;

class ImportDataTest extends TestCase
{

    use VoyagerDataControllerStub;

    /**
     * Emulate table name.
     *
     * @var string
     */
    const POSTS_TABLE_NAME = 'posts';

    /**
     * Value affix.
     *
     * @var string
     */
    private $affix = '_col';

    /**
     * Set const fields.
     *
     * @return void
     */
    public function test_set_const_fields (): void
    {
        $columns = $this->_getColumns(self::POSTS_TABLE_NAME);

        $callBack = function ( int $key, string $value ): string {
            $left_variable = "{$value}{$this->affix}";
            $left_variable = strtoupper($left_variable);
            $field = "const {$left_variable} = {$key};";
            return $field;
        };

        $colNumsArr = array_map( $callBack, array_keys($columns), $columns );
        $this->assertIsArray($colNumsArr);

        $content = $this->_getContent($colNumsArr);
        $this->assertIsString($content);
    }

    /**
     * Set insert maps.
     *
     * @return void
     */
    public function test_set_insert_maps (): void
    {
        $columns = $this->_getColumns(self::POSTS_TABLE_NAME);

        $callBack = function ( string $value ): string {
            $left_variable = "{$value}";
            $const_filed = "{$value}{$this->affix}";
            $const_filed = strtoupper($const_filed);
            $data_arr_str = '$data';
            $right_variable = "{$data_arr_str}[self::$const_filed]";
            $field = "{$left_variable} => {$right_variable},";
            return $field;
        };

        $colNumsArr = array_map( $callBack, $columns );
        $this->assertIsArray($colNumsArr);

        $content = $this->_getContent($colNumsArr);
        $this->assertIsString($content);
    }
}
