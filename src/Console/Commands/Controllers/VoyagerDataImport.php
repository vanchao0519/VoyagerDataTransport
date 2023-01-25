<?php

namespace VoyagerDataTransport\Console\Commands\Controllers;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataControllerCommand;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Services\ImportDataService;

class VoyagerDataImport extends GeneratorCommand implements ICommandStatus
{

    use VoyagerDataCommon;
    use VoyagerDataControllerCommand;
    use VoyagerDataController;

    const CONFIRM_REWRITE_EXIST_FILE_INFO = 'Do you want to rewrite an exist import controller file?';

    const DO_NOT_REWRITE_INFO = 'You select do not rewrite exist import controller file, current process quit! Go next process!';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:import:controller {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data import controller';

    /**
     * Controller name pre.
     *
     * @var string
     */    
    protected $_controllerNamePre = 'Import';

    /**
     * Controller file path.
     *
     * @var string
     */
    protected $_filePath = 'app/VoyagerDataTransport/Http/Controllers/';

    /**
     * Controller file extension.
     *
     * @var string
     */
    protected $_fileExt = '.php';


    /**
     * Get stub
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/ImportDataController.stub');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $tableName = strtolower($this->getNameInput());

        $class = $this->getControllerName($tableName);

        $service = new ImportDataService($tableName);

        return str_replace(
            ['{{ class }}', '{{ tableName }}', '{{ const_fields }}', '{{ import_data_maps }}'],
            [$class, $tableName, $service->setConstFields(), $service->setInsertMaps()],
            $stub,
        );
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $name = $this->getNameInput();

        if ( $this->isFileExists($name) && !$this->confirm(self::CONFIRM_REWRITE_EXIST_FILE_INFO, false)) {
            $this->warn(self::DO_NOT_REWRITE_INFO);
            return self::DO_NOT_REWRITE_CODE;
        }

        $path = $this->getPath($name);

        $this->info("voyager:data:import:controller path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
