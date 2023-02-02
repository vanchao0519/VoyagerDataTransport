<?php

namespace VoyagerDataTransport\Console\Commands\Controllers;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataControllerCommand;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Services\SetSpreadSheetService;

class VoyagerDataExport extends GeneratorCommand implements ICommandStatus
{

    use VoyagerDataCommon;
    use VoyagerDataControllerCommand;
    use VoyagerDataController;

    const CONFIRM_REWRITE_EXIST_FILE_INFO = 'Do you want to rewrite an exist export controller file?';

    const DO_NOT_REWRITE_INFO = 'You select do not rewrite exist export controller file, current process quit! Go next process!';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:export:controller {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data export controller';

    /**
     * Controller name pre.
     *
     * @var string
     */
    protected $_controllerNamePre = 'Export';

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
        return $this->resolveStubPath('/stubs/ExportDataController.stub');
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

        $class = $this->_getControllerName($this->_controllerNamePre, $tableName);

        $setSpreadSheet = new SetSpreadSheetService($tableName);

        return str_replace(
            ['{{ class }}', '{{ tableName }}', '{{ ColNum }}', '{{ ColTitleMaps }}', '{{ ColFieldMaps }}'],
            [$class, $tableName, $setSpreadSheet->setColNum(), $setSpreadSheet->setTitleMaps(), $setSpreadSheet->setFieldMaps()],
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
        $tableName = $this->getNameInput();

        $controllerName = $this->_getControllerName($this->_controllerNamePre, $tableName);

        if ( $this->_isFileExists($controllerName) && !$this->confirm(self::CONFIRM_REWRITE_EXIST_FILE_INFO, false)) {
            $this->warn(self::DO_NOT_REWRITE_INFO);
            return self::DO_NOT_REWRITE_CODE;
        }

        $path = $this->getPath($tableName);

        $this->info("voyager:data:export:controller path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($tableName));

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
