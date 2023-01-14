<?php

namespace VoyagerDataTransport\Console\Commands\Views;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataView;
use Illuminate\Console\GeneratorCommand;

class VoyagerDataImportView extends GeneratorCommand
{

    use VoyagerDataCommon;
    use VoyagerDataView;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:import-data:view {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate voyager data transport import-data blade file';

    /**
     * Generate file name.
     *
     * @var string
     */
    private $_fileName = 'import-data.blade.php';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/import-data.blade.stub');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("voyager:data:transport:import-data:view table name is: {$this->argument('tableName')}");

        $name = $this->getNameInput();

        $path = $this->getPath($name);

        $this->info("voyager:data:transport:import-data:view path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildView());

        return 0;
    }
}
