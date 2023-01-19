<?php


namespace VoyagerDataTransport\Console\Commands\Permission;

use Illuminate\Console\Command;
use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataPermission;
use VoyagerDataTransport\Contracts\ICommandStatus;

class VoyagerDataExportPermission extends Command implements ICommandStatus
{

    use VoyagerDataPermission;

    const CREATING_PERMISSION_RECORD_INFO = 'Import permission data record create successful!';

    const PERMISSION_RECORD_EXISTS_INFO = 'Import permission data record existed!';

    /**
     * Permission record key pre.
     *
     * @var string
     */
    protected $_keyPre = 'browse_export_';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:export:permission {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert voyager export-data-permission to database if response record not exist';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $tableName = strtolower($this->argument('tableName'));
        if (false === $this->isExportPermissionExist($tableName) ) {
            if (0 < $this->createPermission("{$this->_keyPre}{$tableName}", $tableName)) {
                $this->info(self::CREATING_PERMISSION_RECORD_INFO);
            }
        }
        $this->warn(self::PERMISSION_RECORD_EXISTS_INFO);
        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}