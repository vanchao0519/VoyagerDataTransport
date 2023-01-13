<?php


namespace VoyagerDataTransport\Services;

use VoyagerDataTransport\Contracts\IRouteParameters;
use VoyagerDataTransport\Traits\ConfigService;
use Illuminate\Support\Facades\Route;

class WebRoutesService implements IRouteParameters
{
    use ConfigService;

    public function handle (): void
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
        $file = $this->_getAppPath() . '/VoyagerDataTransport/config/route/config.php';
        return $this->_getConfig($file);
    }

    private function regRoute (string $verb, array $dataSets): void {
        $verb = strtolower($verb);
        if (in_array($verb, [self::GET, self::POST])) {
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
