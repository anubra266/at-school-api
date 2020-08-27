<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSockets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sockets {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Sockets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    } 

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Fire off an event
        $message = $this->argument('message');
        event(new \App\Events\TestSocketsMessage($message)); 

    }
}
