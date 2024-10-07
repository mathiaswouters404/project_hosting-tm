<?php

namespace App\Policies;

use App\Question;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function answer(User $user, Question $question)
    {
        $patientId = $question->questionnaire->patient_id;
        return  $user->id == $patientId
            ? Response::allow()
            : Response::deny("You are not allowed to answer this question");
    }



}
