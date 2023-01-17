<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;

/**
 * Class ClearMockDataTest
 * @package Tests\Feature
 */
class ClearMockDataTest extends TestCase
{

    use ParameterTrait;

    /**
     * Remove specific package test folder and file.
     * Removed folder list:
     * app/VoyagerDataTransport
     * resources/vendor
     *
     * @return void
     */
    public function test_remove_local_file(): void
    {
        $this->assertTrue(self::delTree(resource_path(). '/views/vendor'));
        $this->assertTrue(self::delTree(app_path() . '/VoyagerDataTransport'));
    }


    /**
     * Remove specific data records.
     *
     * @return void
     */
    public function test_remove_data_records(): void
    {
        $permissionPres = [
            'browse_import_',
            'browse_export_',
        ];

        $tableName = $this->_getTableName();

        foreach ($permissionPres as $permissionPre) {
            $permissions[] = "{$permissionPre}{$tableName}";
        }

        $count = DB::table('permissions')->count();

        $this->assertIsInt($count);
        $this->assertGreaterThan(0, $count);

        $result = DB::table('permissions')
            ->whereIn('key', $permissions)
            ->where('table_name', '=', $tableName)
            ->delete();

        $resetNum = $count - ($result - 1);

        $this->assertEquals(count($permissions), $result);
        $this->assertTrue(DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT='. $resetNum));
    }

    /**
     * Recursively delete folder and files in folder
     *
     * Reference from the link below:
     * https://www.php.net/manual/zh/function.rmdir.php
     *
     * @param string $dir
     * @return bool
     */
    protected static function delTree(string $dir): bool {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
