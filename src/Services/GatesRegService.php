<?php

namespace VoyagerDataTransport\Services;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use VoyagerDataTransport\Traits\ConfigService;

class GatesRegService
{
    use ConfigService;

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

    public function getConfig ()
    {
        $file = $this->_getAppPath() . '/app/VoyagerDataTransport/config/permissions/config.php';
        return $this->_getConfig($file);
    }

}
