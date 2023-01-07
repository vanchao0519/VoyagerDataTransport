<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use Illuminate\Console\GeneratorCommand;

class VoyagerDataTransportRoute extends GeneratorCommand
{

    use VoyagerDataCommon;

    private $_importPre = 'Import';
    private $_exportPre = 'Export';

    private $_urlImportPre = '/import_';
    private $_urlExportPre = '/export_';

    private $_aliasImportPre = 'voyager.browse_import_';
    private $_aliasExportPre = 'voyager.browse_export_';

    private $_uploadPre = 'voyager.import_';

    const ALL_PROCESS_SUCCESS_CODE = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:route:detail:config {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data transport route detail config file';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/route.detail.config.stub');
    }

    protected function getPath($name): string
    {
        $slug = strtolower($name);

        $path = "app/VoyagerDataTransport/config/route/tables/{$slug}.php";

        return $path;
    }

    protected function replaceConfig($stub)
    {
        $config = $this->_generateConfig();

        $recordArr = array_merge($config['get'], $config['post']);

        $search = [];
        $replace = [];

        foreach ($recordArr as $key => $records) {
            foreach ($records as $pre => $record) {
                $search[] = $this->_getSearchValue($key + 1, "{$pre}_");
                $replace[] = $record;
            }
        }

        return str_replace($search, $replace, $stub);
    }

    protected function buildConfig()
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceConfig($stub);
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $tableName = strtolower($this->getNameInput());

        $path = $this->getPath($tableName);

        $this->info("voyager:data:transport:route:detail:config path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildConfig());

        return self::ALL_PROCESS_SUCCESS_CODE;
    }

    private function _getMapping(string $tableName): array
    {
        $_mapping = [
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'actionName' => function () {
                    return 'index';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}";
                },
            ],
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlExportPre}{$tableName}";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_exportPre, $tableName);
                },
                'actionName' => function () {
                    return 'export';
                },
                'alias' => function () use ($tableName) {
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
                'url' => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}/upload";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'actionName' => function () {
                    return 'upload';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_uploadPre}{$tableName}.upload";
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
