<?php

namespace App\Console\Commands;

use App\Event;
use App\Mail\Reminder;
use App\Mail\ReminderAfter;
use App\Mail\ReminderAfterCareTaker;
use App\Mail\ReminderAtTime;
use App\Mail\ReminderBefore;
use App\Services\Reminders\SendReminderEmailService;
use App\Services\ReminderService;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $sendReminderEmailService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SendReminderEmailService $sendReminderEmailService)
    {
        $this->sendReminderEmailService = $sendReminderEmailService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $minutesNotifyAdvance = 30;

        $currentDateTime = Carbon::now();

        $reminderService = new \App\Services\Reminders\ReminderService($currentDateTime, $minutesNotifyAdvance, $this->sendReminderEmailService);

        $reminderService->sendTaskReminders();
    }
}
