<?php

namespace VoyagerDataTransport\Console\Commands;

use Illuminate\Console\Command;

class VoyagerDataTransport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:data:transport {tableName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command line tools to generate the Controller and the View file which can import data to database and export data to file (excel, csv ,pdf)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tableName = $this->argument('tableName');
        $this->call("voyager:data:import:controller", ['tableName' => $tableName]);
        $this->call("voyager:data:export:controller", ['tableName' => $tableName]);
        $this->call("voyager:data:transport:browse:view", ['tableName' => $tableName]);
        $this->call("voyager:data:transport:import-data:view", ['tableName' => $tableName]);
        return 0;
    }

}