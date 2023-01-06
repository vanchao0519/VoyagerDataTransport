<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use Illuminate\Console\GeneratorCommand;

class VoyagerDataTransportPermissions extends GeneratorCommand
{

    use VoyagerDataCommon;

    const ALL_PROCESS_SUCCESS_CODE = 0;
    const PERMISSION_PRE_IMPORT = 'browse_import_';
    const PERMISSION_PRE_EXPORT = 'browse_export_';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:permission:detail:config {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data transport permission detail config file';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/permission.detail.config.stub');
    }

    protected function getPath($name): string
    {
        $slug = strtolower($name);

        $path = "app/VoyagerDataTransport/config/permissions/tables/{$slug}.php";

        return $path;
    }

    protected function replaceConfig($stub)
    {
        $config = $this->_generateConfig();

        $config01 = $config[0];
        $config02 = $config[1];

        return str_replace(['{{ config01 }}', '{{ config02 }}'], [$config01, $config02], $stub);
    }

    protected function buildConfig()
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceConfig($stub);
    }

    private function _generateConfig(): array
    {
        $_tableName = strtolower($this->getNameInput()) ;

        $_func = function ($_pre, $_table) {
            return "{$_pre}{$_table}";
        };

        $_permissionPre = [
            self::PERMISSION_PRE_IMPORT,
            self::PERMISSION_PRE_EXPORT,
        ];

        $_config = [];

        foreach ($_permissionPre as $_pre) {
            $_config[] = $_func($_pre, $_tableName);
        }

        return $_config;
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

        $this->info("voyager:data:transport:permission:detail:config path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildConfig());

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
