<?php


namespace VoyagerDataTransport\Console\Commands\Traits;

use TCG\Voyager\Models\Permission;

trait VoyagerDataPermission
{

    /**
     * Check permission record is exists.
     *
     * @param  string  $keyPre
     * @param  string  $tableName
     * @return boolean
     */
    protected function isPermissionExist($keyPre, $tableName)
    {
        $key = "{$keyPre}{$tableName}";

        $record = Permission::query()->where([
            'table_name' => $tableName,
            'key' => $key
        ])->first();

        return $record !== null;
    }

    /**
     * Create permission record.
     *
     * @param  string  $key
     * @param  string  $tableName
     * @return int
     */
    protected function createPermission($key, $tableName)
    {
        /** @mixin Permission **/
        $model = new Permission();
        $model->key = $key;
        $model->table_name = $tableName;
        $model->save();
        return $model->id;
    }

    /**
     * Check export permission record is exist.
     *
     * @param  string  $tableName
     * @return boolean
     */
    protected function isExportPermissionExist($tableName)
    {
        return $this->isPermissionExist($this->_keyPre, $tableName);
    }

}