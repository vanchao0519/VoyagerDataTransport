<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataRouteDetailConfig;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Contracts\IRouteParameters;

class VoyagerDataTransportRoute extends GeneratorCommand implements IRouteParameters, ICommandStatus
{

    use VoyagerDataCommon;
    use VoyagerDataRouteDetailConfig;

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
        $tableName = $this->getNameInput();

        $config = $this->_generateConfig($tableName);

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
     * @return bool
     */
    public function handle(): bool
    {
        $tableName = strtolower($this->getNameInput());

        $path = $this->getPath($tableName);

        $this->info("voyager:data:transport:route:detail:config path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildConfig());

        return self::ALL_PROCESS_SUCCESS_CODE;
    }

}
