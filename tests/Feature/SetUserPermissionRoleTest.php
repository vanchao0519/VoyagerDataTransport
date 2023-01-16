<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Traits\ParameterTrait;
use Tests\Feature\Traits\UserTrait;
use Tests\TestCase;

class SetUserPermissionRoleTest extends TestCase
{

    use ParameterTrait;
    use UserTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_set_permission_role()
    {
        $role_id = $this->_getRoleId();
        $permission_ids = $this->_getPermissionIds();
        $this->assertTrue($this->_setPermissionRole($role_id, $permission_ids));
    }

    private function _getPermissionIds()
    {
        $permissionPres = [
            'browse_import_',
            'browse_export_',
        ];

        $tableName = $this->_getTableName();

        foreach ($permissionPres as $permissionPre) {
            $permissions[] = "{$permissionPre}{$tableName}";
        }

        $result = DB::table('permissions')
            ->whereIn('key', $permissions)
            ->where('table_name', "=", "{$tableName}")
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        return $result;
    }

    private function _getRoleId()
    {
        $user = User::query()->where([
            'email' => $this->_email,
        ])->first();

        if ( true === Hash::check( $this->_password, $user->getAuthPassword() ) ) {
            return $user->role_id;
        }
        return false;
    }

    private function _setPermissionRole ( int $role_id, array $permission_ids )
    {
        $data = [];
        foreach ($permission_ids as $pId) {
            $data[] = ['role_id' => $role_id, 'permission_id' => $pId];
        }
        $result = DB::table('permission_role')->insert($data);
        return $result;
    }
}
