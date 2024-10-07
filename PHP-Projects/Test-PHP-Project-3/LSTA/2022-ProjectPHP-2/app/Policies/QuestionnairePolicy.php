<?php

namespace App\Policies;

use App\Questionnaire;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QuestionnairePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Questionnaire  $questionnaire
     * @return mixed
     */
    public function view(User $user, Questionnaire $questionnaire)
    {
        $patientId = $questionnaire->patient_id;
        $userId = $user->id;

        return $userId == $patientId || $user->isPatientFromUser($patientId)
            ? Response::allow()
            : Response::deny("You can't read this answer");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return  $user->role->name == "Dokter" || $user->role->name == "CareTaker"
          ? Response::allow()
          : Response::deny("You are not allowed to create questionnaires");
    }




}
