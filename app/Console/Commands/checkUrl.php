<?php

namespace App\Console\Commands;

use App\Models\Monitor;
use Illuminate\Console\Command;
use App\Http\Controllers\API\V1\MonitorController;

class checkUrl extends Command
{

    /**
     * Create a new controller instance.
     *
     * @param monitor $monitor
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check URL response';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $x = new MonitorController();
        $x->trackingMonitor();
        $this->info('Successfully check the URL.');
        return Command::SUCCESS;
    }
}
