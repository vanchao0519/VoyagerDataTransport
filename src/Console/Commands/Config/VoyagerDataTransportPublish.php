<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use Illuminate\Console\Command;
use VoyagerDataTransport\Contracts\ICommandStatus;
use VoyagerDataTransport\Providers\VoyagerDataTransportProvider;

class VoyagerDataTransportPublish extends Command implements ICommandStatus
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport:publish:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish config files to app/VoyagerDataTransport/config/permissions and app/VoyagerDataTransport/config/route folder';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $this->call('vendor:publish', ['--provider' => VoyagerDataTransportProvider::class]);
        return self::ALL_PROCESS_SUCCESS_CODE;
    }
}
