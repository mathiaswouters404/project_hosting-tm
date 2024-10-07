<?php

namespace App\Providers;

use App\Answer;
use App\Http\Controllers\Questionnaires\QuestionnaireController;
use App\MedicationPatient;
use App\Policies\AgendaPolicy;
use App\Policies\AnswerPolicy;
use App\Policies\EventPolicy;
use App\Policies\PrescriptionPolicy;
use App\Policies\QuestionnairePolicy;
use App\Policies\ViewUserPolicy;
use App\Questionnaire;
use App\User;
use Event;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Answer::class => AnswerPolicy::class,
        Questionnaire::class => QuestionnairePolicy::class,
        User::class => ViewUserPolicy::class,
        MedicationPatient::class => PrescriptionPolicy::class,
        Event::class => EventPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
