<?php

namespace VoyagerDataTransport\Providers;

use Illuminate\Support\ServiceProvider;
use VoyagerDataTransport\Console\Commands\VoyagerDataTransport;
use VoyagerDataTransport\Console\Commands\Controllers\VoyagerDataExport;
use VoyagerDataTransport\Console\Commands\Controllers\VoyagerDataImport;
use VoyagerDataTransport\Console\Commands\Views\VoyagerDataBrowseView;
use VoyagerDataTransport\Console\Commands\Views\VoyagerDataImportView;
use VoyagerDataTransport\Console\Commands\Config\VoyagerDataTransportPermissions;
use VoyagerDataTransport\Console\Commands\Config\VoyagerDataTransportRoute;

class VoyagerDataTransportProvider extends ServiceProvider
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
            VoyagerDataBrowseView::class,
            VoyagerDataImportView::class,
            VoyagerDataTransportPermissions::class,
            VoyagerDataTransportRoute::class,
        ]);
    }
}
