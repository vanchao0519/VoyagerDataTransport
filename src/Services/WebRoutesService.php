<?php


namespace VoyagerDataTransport\Services;

use VoyagerDataTransport\Traits\ConfigService;
use Illuminate\Support\Facades\Route;

class WebRoutesService
{
    use ConfigService;

    const URL = 'url';
    const CONTROLLER = 'controllerName';
    const ACTION = 'actionName';
    const ALIAS = 'alias';

    public function handle ()
    {
        $routes = $this->getConfig();

        if (false !== $routes) {
            foreach ($routes as $routeConfig) {
                foreach ($routeConfig as $verb => $dataSets) {
                    $this->regRoute($verb, $dataSets);
                }
            }
        }

    }

    public function getConfig ()
    {
        $file = $this->_getAppPath() . '/app/VoyagerDataTransport/config/route/config.php';
        return $this->_getConfig($file);
    }

    private function regRoute (string $verb, array $dataSets): void {
        $verb = strtolower($verb);
        if (in_array($verb, ['get', 'post'])) {
            foreach ($dataSets as $dataSet) {
                Route::$verb($dataSet[self::URL], [
                    $dataSet[self::CONTROLLER],
                    $dataSet[self::ACTION]
                ])
                    ->name($dataSet[self::ALIAS])
                    ->middleware('web');
            }
        }
    }

}
