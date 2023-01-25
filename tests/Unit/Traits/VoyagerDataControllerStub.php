<?php


namespace Tests\Unit\Traits;


trait VoyagerDataControllerStub
{
    /**
     * Emulate _getColumns function
     *
     * @param $tableName
     * @return string[]
     */
    private function _getColumns(string $tableName = ''): array
    {
        $columns = [
            'title',
            'body',
            'meta_description',
        ];

        return $columns;
    }

    /**
     * Get the content from colNumsArr
     *
     * @param string[] $colNumsArr
     * @return string
     */
    private function _getContent (array $colNumsArr): string
    {
        $content = '';
        foreach ($colNumsArr as $k => $column) {
            $_tableSignal = 0 === $k ? "" : "\t";
            $content .= "{$_tableSignal}{$column}" . PHP_EOL;
        }
        return $content;
    }

}