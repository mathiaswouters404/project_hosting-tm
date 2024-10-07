<?php

namespace App\Services\Reminders;

use App\Mail\ReminderAfter;
use App\Mail\ReminderAfterCareTaker;
use App\Mail\ReminderAtTime;
use App\Mail\ReminderBefore;
use Mail;

class SendReminderEmailService
{
    public function sendReminderBefore($displayTask) {
        $task = $displayTask['task'];
        $email = new ReminderBefore($displayTask['displayTask']);
        Mail::to($displayTask['emailPatient'])->send($email);
        $task->reminder_sent_before = true;
        $task->save();
    }

    public function sendReminderAtTime($displayTask) {
        $task = $displayTask['task'];
        $email = new ReminderAtTime($displayTask['displayTask']);
        Mail::to($displayTask['emailPatient'])->send($email);
        $task->reminder_sent_before = true;
        $task->reminder_sent_at_time = true;
        $task->save();
    }

    public function sendReminderAfter($displayTask) {
        $task = $displayTask['task'];
        $email = new ReminderAfter($displayTask['displayTask']);
        Mail::to($displayTask['emailPatient'])->send($email);

        $emailsCareTakers = $displayTask['emailsCareTakers'];

        foreach($emailsCareTakers as $emailCareTaker) {
            $careTakerEmail = new ReminderAfterCareTaker($displayTask['displayTask']);
            Mail::to($emailCareTaker)->send($careTakerEmail);
        }

        $task->reminder_sent_before = true;
        $task->reminder_sent_at_time = true;
        $task->reminder_sent_after = true;
        $task->save();
    }
}
