<?php

namespace VoyagerDataTransport\Console\Commands\Views;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataView;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Contracts\ICommandStatus;

class VoyagerDataExportView extends GeneratorCommand implements ICommandStatus
{

    use VoyagerDataCommon;
    use VoyagerDataView;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:export-data:view {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data transport export-data blade file';

    /**
     * Generate file name.
     *
     * @var string
     */
    private $_fileName = 'export-data.blade.php';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/export-data.blade.stub');
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $tableName = $this->getNameInput();

        $this->info("voyager:data:transport:export-data:view table name is: {$tableName}");

        $path = $this->getPath($tableName);

        $this->info("voyager:data:transport:export-data:view path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildView());

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
