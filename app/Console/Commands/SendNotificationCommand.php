<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pusher\Pusher;

class SendNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send {type} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications to client';

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
        $type = $this->argument('type');
        $message = $this->argument('message');

        $pusher = new Pusher('341e2a2f5770505ad5e9',
            '92b74556ce1c0676ff14',
            '580515',
            array('cluster' => "us2"));

        $data = array("type" => $type, "message" => $message);

        $pusher->trigger('my-channel', 'my-event', $data);

    }
}
