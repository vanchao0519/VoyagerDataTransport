<?php


namespace VoyagerDataTransport\Services;

use VoyagerDataTransport\Contracts\IRouteParameters;
use VoyagerDataTransport\Traits\ConfigService;
use Illuminate\Support\Facades\Route;

/**
 * Class WebRoutesService
 * @package VoyagerDataTransport\Services
 */
class WebRoutesService implements IRouteParameters
{
    use ConfigService;

    /**
     * Process handle function
     *
     * @return void
     */
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

    /**
     * Get the array type config
     *
     * @return false|string[][][][]
     */
    public function getConfig ()
    {
        $file = $this->_getAppPath() . '/VoyagerDataTransport/config/route/config.php';
        return $this->_getConfig($file);
    }


    /**
     * Route register
     *
     * @param string $verb
     * @param string[][] $dataSets
     * @return void
     */
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
