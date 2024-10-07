<?php

namespace App\Services\Reminders;

use App\Event;
use App\User;
use Carbon\Carbon;
use Json;
use Ramsey\Uuid\Type\Integer;

class ReminderService
{
    private $currentDateTime;
    private $minutesNotifyAdvance;
    private $minutesAtTime = 2;
    private $reminderTasks;
    private $sendReminderEmailService;

    public function __construct($currentDateTime, $minutesNotifyAdvance, $sendReminderEmailService) {
        $this->currentDateTime = $currentDateTime;
        $this->minutesNotifyAdvance = $minutesNotifyAdvance;
        $this->sendReminderEmailService = $sendReminderEmailService;
        $this->reminderTasks = [
          'before' => [],
          'atTime' => [],
          'after' => []
        ];
    }

    public function sendTaskReminders() {




        $tasks = Event::getTasksToBeReminded($this->currentDateTime, $this->minutesNotifyAdvance);


        foreach($tasks as $task) {


            //check if task is repeating
            if($task->time_unit_id == null) {
                $this->nonRepeatingTask($task);
            } else {
                $this->repeatingTask($task);
            }

        }

        Json::dump($this->reminderTasks);
    }

    public function nonRepeatingTask($task) {
        $remindTask = [
            'task' => $task,
            'startDateTime' => Carbon::create($task->start_date)
        ];
        $this->sendReminderAndUpdateTask($remindTask);
    }

    public function repeatingTask($task) {
        $underLimit = $this->currentDateTime->clone()->addMinutes(-$this->minutesNotifyAdvance);
        $upperLimit = $this->currentDateTime->clone()->addMinutes($this->minutesNotifyAdvance);

        $repetitionService = Repetitionservice::newInstanceFromEvent($task, $underLimit, $upperLimit);

        $repetitions = $repetitionService->getRepetitionsInPeriod();

        if(count($repetitions) == 1) {
            $remindTask = [
                'task' => $task,
                'startDateTime' => $repetitions[0]
            ];
            $this->sendReminderAndUpdateTask($remindTask);
        }

    }

    public function sendReminderAndUpdateTask($remindTask) {
        $difference = $this->currentDateTime->floatDiffInMinutes($remindTask['startDateTime'], false);

        //if task still has to take place
        if($difference >= 0) {
            //if task is about to take place
            if($difference <= $this->minutesAtTime and
                (!$remindTask['task']->reminder_sent_at_time and !$remindTask['task']->reminder_sent_after)){

                $this->sendReminderEmailService->sendReminderAtTime($this->getDisplayTask($remindTask));
            }
            //if task will take place in less or equal the specified amount of minutes
            else if($difference <= $this->minutesNotifyAdvance and
                (!$remindTask['task']->reminder_sent_before and !$remindTask['task']->reminder_sent_at_time and !$remindTask['task']->reminder_sent_after)) {

                $this->sendReminderEmailService->sendReminderBefore($this->getDisplayTask($remindTask));
            }
        }
        //if task has already taken place
        else if($difference <= -$this->minutesNotifyAdvance and !$remindTask['task']->reminder_sent_after) {

            $this->sendReminderEmailService->sendReminderAfter($this->getDisplayTask($remindTask));
        }

    }

    public function getDisplayTask($remindTask) {
        $startDateTime = $remindTask['startDateTime'];
        $duration = $remindTask['task']->duration;
        $endDateTime = $startDateTime->clone()->addMinutes($duration);

        $patient = User::find($remindTask['task']->patient_id);


        $displayTask = [
            'displayTask' => [
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                'name' => $remindTask['task']->name,
                'description' => $remindTask['task']->description,
                'patientName' => $patient->firstName . ' ' . $patient->lastName
                ],
            'task' => $remindTask['task'],
            'emailPatient' => $patient->email,
            'emailsCareTakers' => $patient->careTakerEmails()
        ];

        return $displayTask;
    }

}
