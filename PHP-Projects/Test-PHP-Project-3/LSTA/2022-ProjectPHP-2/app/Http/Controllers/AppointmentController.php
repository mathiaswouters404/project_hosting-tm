<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use App\User;
use Facades\App\Helpers\Json;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Stores the submitted request as an appointment
     * Only users that can access this userId can create an appointment
     *
     * @param Request $request
     * @return mixed
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function storeAppointment(Request $request) {
        $rules = [
            'name'=>'required|min:3',
            'description' => 'required|min:3',
            'start_date' => 'required',
            'duration' => 'required'
        ];

        $customMessages = [
            'name.required' => 'Please fill in the title',
            'name.min' => 'The minimum length is 3 characters',
            'description.required' => 'Please fill in the description',
            'description.min' => 'The minimum length is 3 characters',
            'start_date.required' => 'Please fill in the date and time',
            'duration.required' => 'Please fill in the duration',
        ];

        $this->validate($request, $rules, $customMessages);

        $patient = User::findOrFail($request->patient_id);
        $this->authorize('createAppointment', $patient);

        $this->eventService->storeAppointment($request);

        return Json::createJsonResponse("success","Appointment created");
    }
}
