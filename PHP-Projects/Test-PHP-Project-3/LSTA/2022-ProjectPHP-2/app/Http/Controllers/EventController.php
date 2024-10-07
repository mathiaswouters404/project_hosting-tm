<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventType;
use App\MedicationPatient;
use App\Services\EventService;
use App\TimeUnit;
use App\User;
use Exception;
use Facades\App\Helpers\Json;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function response;
use function view;

class EventController extends Controller
{
    private $eventService;

    public function __construct(EventService $eventService) {
        $this->eventService = $eventService;
    }



    // Crud methods

    /**
     * Shows the agenda for the given user id
     *
     * @param $id
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index($id) {
        $patient = User::find($id);

        $this->authorize('view', $patient);

        $result = compact('patient');

        return view("agenda.agenda", $result);
    }

    /**
     * Stores a new event in the database
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request) : JsonResponse
    {
        $eventTypeId = $this->validateEventType($request);

        $rules = $this->getEventValidationRules($request, $eventTypeId);
        $this->validate($request, $rules['rules'], $rules['messages']);

        if (!$this->eventService->validateRequest($request)) {
            return response()->json([
                'errors' => [
                    'name' => ["The duration cannot be negative"]
                ]
            ])->setStatusCode(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $title = $this->eventService->store($request);

        return response()->json([
            'type' => 'success',
            'text' => "The event $title is successfully saved"
        ]);
    }

    /**
     * Updates the event in the database
     *
     * @param Request $request
     * @param int $id
     * @return void
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        if (!$this->eventService->validateRequest($request)) {
            return response()->json([
                'errors' => [
                    'name' => ["The duration cannot be negative"]
                ]
            ])->setStatusCode(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $event = Event::findOrFail($id);

        $this->authorize('update', $event);

        $event_type_id = $event->event_type_id;

        if ($event_type_id == 4) {
            $rules = $this->getQuestionnaireValidationRules();
            $this->validate($request, $rules['rules'], $rules['messages']);

            $this->eventService->updateQuestionnaire($request, $event);
        } else {
            $rules = $this->getEditEventValidationRules($request, $event_type_id);
            $this->validate($request, $rules['rules'], $rules['messages']);

            $this->eventService->update($request, $event);
        }

        return response()->json([
            'type' => 'success',
            'text' => 'The event is successfully updated'
        ]);
    }

    /**
     * Remove the event
     *
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        $event->delete();
        return response()->json([
            'type' => 'success',
            'text' => "The event <b>$event->name</b> has been deleted"
        ]);
    }



    // Query methods

    /**
     * Queries the event with the specified id
     *
     * @param $id
     * @return mixed
     */
    public function qryEvent($id) {
        return $this->eventService->qryEvent($id);
    }

    /**
     * Returns the events that will occur in that week
     * This can be the events for the current user (the id is null)
     * This can be the events for one of your patients (the id is given as a parameter)
     * If an id is requested you don't have access to, an error page will show up
     *
     * @param int|null $id
     * @return Collection
     * @throws AuthorizationException
     */
    public function queryWeeklyEventsWithInfo(Request $request, int $id = null): Collection
    {
        $patient = User::find($id);
        $this->authorize('view', $patient);

        return $this->eventService->getWeeklyEventsWithInfo($request->monday, $id);
    }

    public function queryWeeklyEventsWithoutInfo(Request $request, int $id = null): Collection
    {
        $patient = User::find($id);
//        $this->authorize('view', $patient);

        return $this->eventService->getWeeklyEventsWithoutInfo($request->monday, $id);
    }

    /**
     * Gets the medication for the given user
     * If an id is requested you don't have access to, you can't access this resource
     *
     * @param $id
     * @return mixed
     * @throws AuthorizationException
     */
    public function qryExMedications($id) {
        $patient = User::find($id);

        $this->authorize('view', $patient);

        return MedicationPatient::select('medication_patient_events.id as medication_patient_event_id', 'medication_patients.id', 'medication_id')
            ->with('medication')
            ->leftJoin('medication_patient_events', 'medication_patients.id', '=', 'medication_patient_events.medication_patient_id')
            ->get();
    }

    /**
     * Queries the time units
     *
     * @return mixed
     */
    public function qryTimeUnits() {
        $timeUnits = TimeUnit::select('id', 'name')->get();

        return $timeUnits;
    }

    /**
     * Queries the event types
     *
     * @return mixed
     */
    public function queryEventTypes() {
        return EventType::get()
            ->makeHidden(['approval_needed', 'frequency', 'created_at', 'updated_at']);
    }



    // Confirm event
    /**
     * Confirms the given event
     *
     * @param int $id
     * @return JsonResponse
     */
    public function confirmEvent(int $id) {
        $event = Event::find($id);

        $this->authorize('confirm', $event);
        $this->eventService->confirmEvent($id);

        return response()->json([
            'type' => 'success',
            'text' => "The event is confirmed"
        ]);
    }



    // Exclude specific date
    /**
     * Excludes a certain date for the repeating event
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function excludeDate(Request $request) {
        $date = $this->eventService->excludeDate($request);

        return response()->json([
            'type' => 'success',
            'text' => "The event has been deleted on '<b>$date</b>'"
        ]);
    }



    // Validation

    /**
     * Checks if the event type of the request is set and returns it
     *
     * @param $request
     * @return int
     * @throws ValidationException
     */
    private function validateEventType($request): int
    {
        $this->validate($request, [
            "event_type_id" => "required"
        ], [
            "event_type_id.required" => "You cannot create an event without an event type"
        ]);

        return $request["event_type_id"];
    }

    /**
     * Returns the validation rules and messages for the event type
     *
     * @param $request
     * @param $eventTypeId
     * @return array[]
     */
    private function getEventValidationRules($request, $eventTypeId): array
    {
        // Init arrays and event type id
        $rules = [];
        $messages = [];

        // Start date
        $rules['start_date'] = ['required'];

        // Free task or appointment
        if ($eventTypeId == 1 || $eventTypeId == 2) {
            // Name
            $rules["name"] = ["required", "min:3"];
            $messages["name.required"] = "Please fill in the title";
            $messages["name.min"] = "The minimum length is 3 characters";

            // Description
            $rules["description"] = ["required", "min:3"];
            $messages["description.required"] = "Please fill in the description";
            $messages["description.min"] = "The minimum length is 3 characters";
        }

        // Questionnaire
        else if ($eventTypeId == 4) {
            // Questionnaire id
            $rules['questionnaire_id'] = ['required', "numeric"];
            $messages['questionnaire_id.required'] = "Please select a questionnaire";
            $messages['questionnaire_id.numeric'] = "Please select a questionnaire";
        }

        // Repetition
        if($request['repeat'] != null) {
            // Interval
            $rules['interval'] = ['required', 'numeric', 'gt:0'];
            $messages['interval.required'] = 'Choose an interval';

            // Time unit id
            $rules['time_unit_id'] = ['required'];
            $messages['time_unit_id.required'] = 'Choose a time unit';
        }

        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }

    /**
     * Returns the validation rules and messages for editing an event
     *
     * @param $request
     * @param $eventTypeId
     * @return array|array[]
     */
    private function getEditEventValidationRules($request, $eventTypeId): array
    {
        $validation = $this->getEventValidationRules($request, $eventTypeId);

        // Event type
        $validation['rules']['event_type_id'] = ['prohibited'];
        $validation['messages']['event_type_id.prohibited'] = 'You cannot update the event type';

        if ($eventTypeId == 4) {
            // Interval
            $validation['rules']['interval'] = ['prohibited'];
            $validation['messages']['interval.prohibited'] = 'You cannot update the interval of a questionnaire';

            // Time unit
            $validation['rules']['time_unit_id'] = ['prohibited'];
            $validation['messages']['time_unit_id.prohibited'] = 'You cannot update the time unit of a questionnaire';

            // Duration
            $validation['rules']['duration'] = ['prohibited'];
            $validation['messages']['duration.prohibited'] = 'You cannot update the duration of a questionnaire';
        }

        return $validation;
    }

    /**
     * Returns the validation rules for editing a questionnaire
     *
     * @return array[]
     */
    private function getQuestionnaireValidationRules(): array
    {
        $rules = [];
        $messages = [];

        $rules["name"] = ["prohibited"];
        $messages["name.prohibited"] = ["You cannot update the title of a questionnaire"];

        $rules["description"] = ["prohibited"];
        $messages["description.prohibited"] = ["You cannot update the  of a questionnaire"];

        $rules["location"] = ["prohibited"];
        $messages["location.prohibited"] = ["You cannot update the location of a questionnaire"];

        $rules["confirmed"] = ["prohibited"];
        $messages["confirmed.prohibited"] = ["You cannot confirm a questionnaire"];

        $rules["duration"] = ["prohibited"];
        $messages["duration.prohibited"] = ["You cannot update the duration of a questionnaire"];

        $rules["interval"] = ["prohibited"];
        $messages["interval.prohibited"] = ["You cannot update the interval of a questionnaire"];

        $rules["contact_person"] = ["prohibited"];
        $messages["contact_person.prohibited"] = ["You cannot update the contact person of a questionnaire"];

        $rules["questionnaire_id"] = ["prohibited"];
        $messages["questionnaire_id.prohibited"] = ["You cannot update the questionnaire of a questionnaire"];

        $rules["patient_id"] = ["prohibited"];
        $messages["patient_id.prohibited"] = ["You cannot update the patient of a questionnaire"];

        $rules["time_unit_id"] = ["prohibited"];
        $messages["time_unit_id.prohibited"] = ["You cannot update the time unit of a questionnaire"];

        $rules["event_type_id"] = ["prohibited"];
        $messages["event_type_id.prohibited"] = ["You cannot update the event type"];

        $rules["end_date"] = ["prohibited"];
        $messages["end_date.prohibited"] = ["You cannot update the end date of a questionnaire"];

        return [
            "rules" => $rules,
            "messages" => $messages
        ];
    }
}
