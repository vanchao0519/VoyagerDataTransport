<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

trait VoyagerDataRouteDetailConfig
{
    private $_importPre = 'Import';
    private $_exportPre = 'Export';

    private $_urlImportPre = '/import_';
    private $_urlExportPre = '/export_';

    private $_aliasImportPre = 'voyager.browse_import_';
    private $_aliasExportPre = 'voyager.browse_export_';

    private $_uploadPre = 'voyager.import_';
    private $_downloadPre = 'voyager.export_';

    private function _getMapping(string $tableName): array
    {
        $_mapping = [
            [
                self::URL => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}";
                },
                self::CONTROLLER => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                self::ACTION => function () {
                    return 'index';
                },
                self::ALIAS => function () use ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}";
                },
            ],
            [
                self::URL => function () use ($tableName) {
                    return "{$this->_urlExportPre}{$tableName}";
                },
                self::CONTROLLER => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_exportPre, $tableName);
                },
                self::ACTION => function () {
                    return 'export';
                },
                self::ALIAS => function () use ($tableName) {
                    return "{$this->_aliasExportPre}{$tableName}";
                },
            ],
        ];

        return $_mapping;
    }

    private function _postMapping(string $tableName): array
    {
        $_mapping = [
            [
                self::URL => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}/upload";
                },
                self::CONTROLLER => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                self::ACTION => function () {
                    return 'upload';
                },
                self::ALIAS => function () use ($tableName) {
                    return "{$this->_uploadPre}{$tableName}.upload";
                },
            ],
            [
                self::URL => function () use ($tableName) {
                    return "{$this->_urlExportPre}{$tableName}/download";
                },
                self::CONTROLLER => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_exportPre, $tableName);
                },
                self::ACTION => function () {
                    return 'download';
                },
                self::ALIAS => function () use ($tableName) {
                    return "{$this->_downloadPre}{$tableName}.download";
                },
            ],
        ];

        return $_mapping;
    }

    private function _controllerNameGenerate(string $_namePre, string $_tableName): string
    {

        $pre = "App\VoyagerDataTransport\Http\Controllers";

        $tableName = strtolower($_tableName);
        $tempArr = explode("_", $tableName);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return "{$pre}\\{$_namePre}{$baseName}";
    }

    private function _generateConfig(): array
    {
        $tableName = strtolower($this->getNameInput());

        $routeMappings = [
            'get' => $this->_getMapping($tableName),
            'post' => $this->_postMapping($tableName),
        ];

        $routeConfig = [];

        foreach ($routeMappings as $_verb => $_mappings) {
            foreach ($_mappings as $_mKey => $_functions) {
                foreach ($_functions as $_fKey => $_function) {
                    $routeConfig[$_verb][$_mKey][$_fKey] = $_function();
                }
            }
        }

        return $routeConfig;
    }

    private function _getSearchValue(int $key, string $pre): string
    {
        return "{{ {$pre}{$key} }}";
    }
}