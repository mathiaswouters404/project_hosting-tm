<?php

namespace App\Http\Controllers\Debug;

use App\Event;
use App\EventType;
use App\ExcludedDate;
use App\Http\Controllers\Controller;
use App\PatientCarePersonnel;
use App\Role;
use App\Services\Reminders\ReminderService;
use App\Services\Reminders\RepetitionService;
use App\Services\Reminders\SendReminderEmailService;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Json;
use function MongoDB\BSON\toJSON;

//THIS IS A TEST CONTROLLER JUST MOVE ON

class TaskController extends Controller
{
    public function testPolicy() {
        $user = User::find(4);
        $event = Event::find(4);

        $fromUser = $this->isEventFromUser($user, $event);
        Json::dump(compact('user', 'event', 'fromUser'));
        $fromPatient = $this->isEventFromPatient($user, $event);

        if($fromUser and $this->canConfirm($user)) {
            $allowed =  true;
        } else if($fromPatient) {
            $allowed =  true;
        } else {
            $allowed =  false;
        }

        $result = compact('allowed');

        return $result;
    }

    private function isEventFromUser(User $user, Event $event): bool
    {
        if ($event->isEventFromUserId($user->id)) {
            return true;
        } else {
            return false;
        }
    }

    private function isEventFromPatient(User $user, Event $event) {
        $patient = $event->patient;
        return $user->isPatientFromUser($patient->id);
    }

    private function canConfirm(User $user) {
        $patientRightsNames = $user->getPatientRightsNames();
        return in_array('complete_tasks', $patientRightsNames);
    }

    public function sendRemindersTest() {
        $minutesNotifyAdvance = 30;

        $currentDateTime = Carbon::create(2022, 5, 26, 8, 30);

        $sendReminderEmailService = new SendReminderEmailService();

        $reminderService = new ReminderService($currentDateTime, $minutesNotifyAdvance, $sendReminderEmailService);

        $reminderService->sendTaskReminders();
    }

    public function resetRepeatingTasksTest() {
        $repeatingTasks = Event::getRepeatingTasks();


        foreach($repeatingTasks as $task) {
            $task->confirmed = false;
            $task->reminder_sent_before = false;
            $task->reminder_sent_at_time = false;
            $task->reminder_sent_after = false;
            $task->save();
        }
    }

    public function repetitionExcludedDates() {
        $event = Event::find(7);

        $startDateTime = Carbon::create(2022, 5, 12)->startOfDay();
        $endDateTime = $startDateTime->clone()->endOfDay()->addDays(7);

        $repetitionService = RepetitionService::newInstanceFromEvent($event, $startDateTime, $endDateTime);

        return $repetitionService->getRepetitionsInPeriodFormatted();
    }
}
