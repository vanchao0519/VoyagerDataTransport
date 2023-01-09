<?php

namespace VoyagerDataTransport\Console\Commands\Config;

use Illuminate\Console\Command;

class VoyagerDataTransportPublish extends Command
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
     * @return int
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => VoyagerDataTransportProvider::class]);
        return 0;
    }
}
