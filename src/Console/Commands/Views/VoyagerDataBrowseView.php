<?php

namespace VoyagerDataTransport\Console\Commands\Views;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataView;
use Illuminate\Console\GeneratorCommand;
use VoyagerDataTransport\Contracts\ICommandStatus;

class VoyagerDataBrowseView extends GeneratorCommand implements ICommandStatus
{

    use VoyagerDataCommon;
    use VoyagerDataView;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:browse:view {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data transport browse blade file';

    /**
     * Generate file name.
     *
     * @var string
     */
    private $_fileName = 'browse.blade.php';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/browse.blade.stub');
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $tableName = $this->getNameInput();

        $this->info("voyager:data:transport:browse:view table name is: {$tableName}");

        $path = $this->getPath($tableName);

        $this->info("voyager:data:transport:browse:view path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildView());

        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
