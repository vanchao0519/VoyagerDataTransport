<?php

namespace VoyagerDataTransport\Console\Commands\Controllers;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;
use Illuminate\Console\GeneratorCommand;

class VoyagerDataExport extends GeneratorCommand
{

    use VoyagerDataCommon;
    use VoyagerDataController;

    const CREATING_PERMISSION_RECORD_INFO = 'Export permission data record create successful!';

    const PERMISSION_RECORD_EXISTS_INFO = 'Export permission data record existed!';

    const CONFIRM_REWRITE_EXIST_FILE_INFO = 'Do you want to rewrite an exist export controller file?';

    const DO_NOT_REWRITE_INFO = 'You select do not rewrite exist export controller file, current process quit! Go next process!';

    const DO_NOT_REWRITE_CODE = -20;

    const ALL_PROCESS_SUCCESS_CODE = 0;

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
     * Permission record key pre.
     *
     * @var string
     */
    protected $_keyPre = 'browse_export_';

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

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/ExportDataController.stub');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tableName = strtolower($this->getNameInput());

        $name = $this->qualifyClass($this->getNameInput());

        if (false === $this->isExportPermissionExist($tableName) ) {
            if (0 < $this->createPermission("{$this->_keyPre}{$tableName}", $tableName)) {
                $this->info(self::CREATING_PERMISSION_RECORD_INFO);
            }
        } else {
            $this->warn(self::PERMISSION_RECORD_EXISTS_INFO);
        }

        if ( $this->isFileExists() && !$this->confirm(self::CONFIRM_REWRITE_EXIST_FILE_INFO, false)) {
            $this->warn(self::DO_NOT_REWRITE_INFO);
            return self::DO_NOT_REWRITE_CODE;
        }

        $path = $this->getPath($name);

        $this->info("voyager:data:export:controller path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
