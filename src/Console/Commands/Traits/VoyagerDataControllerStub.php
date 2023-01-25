<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

use Illuminate\Support\Facades\Schema;

trait VoyagerDataControllerStub
{
    /**
     * Get the data table columns
     *
     * @return string[]
     */
    private function _getColumns(): array
    {
        $hasTable = Schema::hasTable($this->_tableName);

        $columns = ['title'];

        if ( $hasTable ) {
            $columns = Schema::getColumnListing($this->_tableName);
            $columns = array_filter($columns, function ($value) {
                $excepts = ['id'];
                return !in_array($value, $excepts);
            });

            $columns = array_values($columns);
        }

        return $columns;
    }

    /**
     * Get the content from colNumsArr
     *
     * @param string[] $colNumsArr
     * @param string $tableSignal
     * @return string
     */
    private function _getContent (array $colNumsArr, string $tableSignal = "\t\t\t"): string
    {
        $content = '';

        foreach ($colNumsArr as $k => $column) {
            $_tableSignal = 0 === $k ? "" : "{$tableSignal}";
            $content .= "{$_tableSignal}{$column}" . PHP_EOL;
        }

        return $content;
    }

}