<?php

namespace VoyagerDataTransport\Console\Commands\Traits;

/**
 * Trait VoyagerDataRouteDetailConfig
 * @package VoyagerDataTransport\Console\Commands\Traits
 */
trait VoyagerDataRouteDetailConfig
{

    /**
     * Import controller pre
     *
     * @var string
     */
    private $_importPre = 'Import';

    /**
     * Export controller pre
     *
     * @var string
     */
    private $_exportPre = 'Export';

    /**
     * Import url pre
     *
     * @var string
     */
    private $_urlImportPre = '/import_';

    /**
     * Export url pre
     *
     * @var string
     */
    private $_urlExportPre = '/export_';

    /**
     * Import alias pre
     *
     * @var string
     */
    private $_aliasImportPre = 'voyager.browse_import_';

    /**
     * Export alias pre
     *
     * @var string
     */
    private $_aliasExportPre = 'voyager.browse_export_';

    /**
     * Upload alias pre
     *
     * @var string
     */
    private $_uploadPre = 'voyager.import_';

    /**
     * Download alias pre
     *
     * @var string
     */
    private $_downloadPre = 'voyager.export_';


    /**
     * Route [GET] mapping
     *
     * @param string $tableName
     * @return \Closure[][]
     */
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

    /**
     * Route [POST] mapping
     *
     * @param string $tableName
     * @return \Closure[][]
     */
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

    /**
     * Generate controller name
     *
     * @param string $_namePre
     * @param string $_tableName
     * @return string
     */
    private function _controllerNameGenerate(string $_namePre, string $_tableName): string
    {
        $pre = "App\VoyagerDataTransport\Http\Controllers";

        $controllerName = $this->_getControllerName($_namePre, $_tableName);

        return "{$pre}\\{$controllerName}";
    }

}