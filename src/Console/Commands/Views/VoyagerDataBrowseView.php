<?php

namespace VoyagerDataTransport\Console\Commands\Views;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataCommon;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataView;
use Illuminate\Console\GeneratorCommand;

class VoyagerDataBrowseView extends GeneratorCommand
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

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/browse.blade.stub');
    }

    /**
     * Rewrite getPath function.
     *
     * @return string
     */
    protected function getPath($name)
    {
        $slug = strtolower($name);

        $path = "resources/views/vendor/voyager/{$slug}/browse.blade.php";

        return $path;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("voyager:data:transport:browse:view table name is: {$this->argument('tableName')}");

        $name = $this->getNameInput();

        $path = $this->getPath($name);

        $this->info("voyager:data:transport:browse:view path name is: {$path}");

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildView());

        return 0;
    }
}
