<?php

namespace App\Policies;

use App\Event;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return void
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Event $event
     * @return void
     */
    public function view(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return void
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Event $event
     * @return Response
     */
    public function update(User $user, Event $event)
    {
        return $this->isAllowedToEditEvent($user, $event)
            ? Response::allow()
            : Response::deny('You are not allowed to update this event');
    }

    public function confirm(User $user, Event $event) {
        return $this->isAllowedToConfirmEvent($user, $event)
            ? Response::allow()
            : Response::deny("You are not allow to confirm this event");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Event $event
     * @return Response
     */
    public function delete(User $user, Event $event)
    {
        return $this->isAllowedToEditEvent($user, $event)
            ? Response::allow()
            : Response::deny('You are not allowed to delete this event');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Event $event
     * @return void
     */
    public function restore(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Event $event
     * @return void
     */
    public function forceDelete(User $user, Event $event)
    {
        //
    }

    private function isAllowedToConfirmEvent(User $user, Event $event): bool {


        $fromUser = $this->isEventFromUser($user, $event);
        $fromPatient = $this->isEventFromPatient($user, $event);


        if($fromUser and $this->canConfirm($user)) {
            return true;
        } else if($fromPatient) {
            return true;
        } else {
            return false;
        }

    }

    private function isAllowedToEditEvent(User $user, Event $event): bool {
        $fromUser = $this->isEventFromUser($user, $event);
        $fromPatient = $this->isEventFromPatient($user, $event);

        if($fromUser) {
            return true;
        } else if($fromPatient) {
            return true;
        } else {
            return false;
        }
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
}
