<?php

namespace VoyagerDataTransport\Providers;

use Illuminate\Support\ServiceProvider;
use VoyagerDataTransport\Console\Commands\VoyagerDataTransport;
use VoyagerDataTransport\Console\Commands\Controllers\VoyagerDataExport;
use VoyagerDataTransport\Console\Commands\Controllers\VoyagerDataImport;

class CmdHelloWorldProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->commands([
            VoyagerDataTransport::class,
            VoyagerDataImport::class,
            VoyagerDataExport::class,
        ]);
    }
}
