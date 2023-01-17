<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\Feature\Traits\ParameterTrait;
use Tests\TestCase;

class ClearMockDataTest extends TestCase
{
    use ParameterTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_remove_local_file(): void
    {
        $this->assertTrue(self::delTree(resource_path(). '/views/vendor'));
        $this->assertTrue(self::delTree(app_path() . '/VoyagerDataTransport'));
    }

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

        $id = DB::table('permissions')
                ->orderBy('id', 'desc')
                ->limit(1)
                ->value('id');

        $this->assertIsInt($id);

        $result = DB::table('permissions')
            ->whereIn('key', $permissions)
            ->where('table_name', '=', $tableName)
            ->delete();

        $resetNum = $id - ($result - 1);

        $this->assertEquals(count($permissions), $result);
        $this->assertTrue(DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT='. $resetNum));
    }

    protected static function delTree($dir): bool {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
