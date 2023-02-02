<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataRouteDetailConfig;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerGetControllerName;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Contracts\IRouteParameters;

/**
 * Class VoyagerDataTransportRoute
 * @package VoyagerDataTransport\Console\Commands\Config
 */
class VoyagerDataTransportRoute extends GeneratorCommand implements IRouteParameters, ICommandStatus
{

    use VoyagerDataCommon;
    use VoyagerGetControllerName;
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


    /**
     * Get stub
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/route.detail.config.stub');
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

        $path = "app/VoyagerDataTransport/config/route/tables/{$slug}.php";

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
        $tableName = $this->getNameInput();

        $search = [
            '{{ get_mappings }}',
            '{{ post_mappings }}',
        ];

        $getMappings = $this->_getMapping($tableName);
        $postMappings = $this->_postMapping($tableName);

        $replace = [
            $this->_setMappings($getMappings),
            $this->_setMappings($postMappings),
        ];

        return str_replace($search, $replace, $stub);
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
     * Set stub replace mappings
     *
     * @param array< array<string, \Closure> > $mappings
     * @return string
     */
    private function _setMappings (array $mappings): string
    {
        $keys = [
            self::URL,
            self::CONTROLLER,
            self::ACTION,
            self::ALIAS,
        ];

        $callBack = function ( array $setting ) use ($keys) : array {
            $array = [];
            foreach ($keys as $key) $array[$key] = $setting[$key]();
            return $array;
        };

        $content = array_map($callBack, $mappings);

        $tableSignal = "\t";

        $callBack = function ( array $setting ) use ($keys, $tableSignal) : string {
            $content = '';
            foreach ($keys as $key => $value) {
                $content .= "{$tableSignal}{$tableSignal}{$tableSignal}'{$value}' => '{$setting[$value]}'," . PHP_EOL;
            }
            $content = "{$tableSignal}{$tableSignal}[". PHP_EOL ."{$content}{$tableSignal}{$tableSignal}],". PHP_EOL;
            return $content;
        };

        $content = array_map($callBack, $content);

        $content = implode($content);

        $content = "[". PHP_EOL ."{$content}{$tableSignal}],";

        return $content;
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
