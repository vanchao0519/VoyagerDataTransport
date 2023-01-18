<?php

namespace VoyagerDataTransport\Providers;

use Illuminate\Support\ServiceProvider;
use VoyagerDataTransport\Console\Commands\Permission\VoyagerDataExportPermission;
use VoyagerDataTransport\Console\Commands\Permission\VoyagerDataImportPermission;
use VoyagerDataTransport\Console\Commands\Views\VoyagerDataExportView;
use VoyagerDataTransport\Console\Commands\VoyagerDataTransport;
use VoyagerDataTransport\Console\Commands\Controllers\VoyagerDataExport;
use VoyagerDataTransport\Console\Commands\Controllers\VoyagerDataImport;
use VoyagerDataTransport\Console\Commands\Views\VoyagerDataBrowseView;
use VoyagerDataTransport\Console\Commands\Views\VoyagerDataImportView;
use VoyagerDataTransport\Console\Commands\Config\VoyagerDataTransportPermissions;
use VoyagerDataTransport\Console\Commands\Config\VoyagerDataTransportRoute;
use VoyagerDataTransport\Console\Commands\Config\VoyagerDataTransportPublish;
use VoyagerDataTransport\Services\GatesRegService;

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

        $_publishPath = 'VoyagerDataTransport/config';

        $this->publishes([
            dirname(__DIR__, 1) . "/config/permissions/config.php" => app_path("{$_publishPath}/permissions/config.php"),
            dirname(__DIR__, 1) . "/config/route/config.php" => app_path("{$_publishPath}/route/config.php"),
        ]);

        $this->commands([
            VoyagerDataTransport::class,
            VoyagerDataImportPermission::class,
            VoyagerDataExportPermission::class,
            VoyagerDataImport::class,
            VoyagerDataExport::class,
            VoyagerDataBrowseView::class,
            VoyagerDataImportView::class,
            VoyagerDataExportView::class,
            VoyagerDataTransportPermissions::class,
            VoyagerDataTransportRoute::class,
            VoyagerDataTransportPublish::class,
        ]);

        (new GatesRegService)->handle();

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }
}
