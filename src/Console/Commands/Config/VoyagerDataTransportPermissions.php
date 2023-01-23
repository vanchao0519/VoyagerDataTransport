<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Contracts\IPermissionParameters;

/**
 * Class VoyagerDataTransportPermissions
 * @package VoyagerDataTransport\Console\Commands\Config
 */
class VoyagerDataTransportPermissions extends GeneratorCommand implements IPermissionParameters, ICommandStatus
{

    use VoyagerDataCommon;

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


    /**
     * Get stub
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/permission.detail.config.stub');
    }


    /**
     * Get path
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $slug = strtolower($name);

        $path = "app/VoyagerDataTransport/config/permissions/tables/{$slug}.php";

        return $path;
    }

    /**
     * Replace config
     *
     * @param string $stub
     * @return string
     */
    protected function replaceConfig(string $stub)
    {
        $config = $this->_generateConfig();

        $getKeyString = function (int $key, string $pre): string {
            return "{{ {$pre}{$key} }}";
        };

        $search = [];

        foreach ($config as $key => $value) {
            $search[$key] = $getKeyString($key + 1, 'config_');
        }

        return str_replace($search, $config, $stub);
    }

    /**
     * Build config
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildConfig()
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceConfig($stub);
    }


    /**
     * Generate config data
     *
     * @return string[]
     */
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
     * @return bool
     */
    public function handle(): bool
    {
        $tableName = strtolower($this->getNameInput());

        $path = $this->getPath($tableName);

        $this->info("voyager:data:transport:permission:detail:config path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildConfig());

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
