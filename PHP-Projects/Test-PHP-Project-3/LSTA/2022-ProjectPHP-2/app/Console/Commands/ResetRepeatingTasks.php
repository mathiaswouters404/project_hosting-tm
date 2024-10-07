<?php

namespace App\Console\Commands;

use App\Event;
use Illuminate\Console\Command;

class ResetRepeatingTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:repeatingTasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $repeatingTasks = Event::getRepeatingTasks();


        foreach($repeatingTasks as $task) {
            $task->confirmed = false;
            $task->reminder_sent_before = false;
            $task->reminder_sent_at_time = false;
            $task->reminder_sent_after = false;
            $task->save();
        }
    }
}
