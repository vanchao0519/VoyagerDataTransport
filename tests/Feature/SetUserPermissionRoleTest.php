<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Traits\ParameterTrait;
use Tests\Feature\Traits\UserTrait;
use Tests\TestCase;

/**
 * Class SetUserPermissionRoleTest
 * @package Tests\Feature
 */
class SetUserPermissionRoleTest extends TestCase
{

    use ParameterTrait;
    use UserTrait;

    /**
     * Set user permission role for next test.
     *
     * @return void
     */
    public function test_set_permission_role(): void
    {
        $role_id = $this->_getRoleId();
        $this->assertIsInt($role_id);
        $permission_ids = $this->_getPermissionIds();
        $this->assertIsArray($permission_ids);
        $this->assertTrue($this->_setPermissionRole($role_id, $permission_ids));
    }


    /**
     * Get permission id from data table permission
     *
     * @return array
     */
    private function _getPermissionIds(): array
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


    /**
     * Get role id from data table user
     *
     * @return int
     */
    private function _getRoleId(): int
    {
        $user = User::query()->where([
            'email' => $this->_email,
        ])->first();

        if ( true === Hash::check( $this->_password, $user->getAuthPassword() ) ) {
            return $user->role_id;
        }
        return false;
    }


    /**
     * Insert record to data table permission_role
     *
     * @param int $role_id
     * @param array $permission_ids
     * @return bool
     */
    private function _setPermissionRole ( int $role_id, array $permission_ids ): bool
    {
        $data = [];
        foreach ($permission_ids as $pId) {
            $data[] = ['role_id' => $role_id, 'permission_id' => $pId];
        }
        $result = DB::table('permission_role')->insert($data);
        return $result;
    }
}
