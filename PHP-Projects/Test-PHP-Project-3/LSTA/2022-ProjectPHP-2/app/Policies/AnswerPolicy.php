<?php

namespace App\Policies;

use App\Answer;
use App\Question;
use App\Questionnaire;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param  \App\Answer  $answer
     * @return mixed
     */
    public function view(User $user, int $questionnaireId)
    {
        $questionnaire = Questionnaire::findById($questionnaireId);
       $patientId = $questionnaire->patient_id;
       $userId = $user->id;
        return $userId == $patientId || $user->isPatientFromUser($patientId)
            ? Response::allow()
            : Response::deny("You can't read this answer");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role->name == "Patient"
            ? Response::allow()
            : Response::deny("You can't answer");
    }

}
