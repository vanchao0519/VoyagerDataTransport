<?php

namespace VoyagerDataTransport\Services;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use VoyagerDataTransport\Traits\ConfigService;

/**
 * Class GatesRegService
 * @package VoyagerDataTransport\Services
 */
class GatesRegService
{
    use ConfigService;

    /**
     * Process handle function
     *
     * @return void
     */
    public function handle ()
    {
        $permissions = $this->getConfig();

        if (false !== $permissions) {
            foreach ( $permissions as $permission ) {
                Gate::define($permission, function (User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }

    /**
     * Get the array type config
     *
     * @return false|string[]
     */
    public function getConfig ()
    {
        $file = $this->_getAppPath() . '/VoyagerDataTransport/config/permissions/config.php';
        /** @var false|string[] **/
        return $this->_getConfig($file);
    }

}
