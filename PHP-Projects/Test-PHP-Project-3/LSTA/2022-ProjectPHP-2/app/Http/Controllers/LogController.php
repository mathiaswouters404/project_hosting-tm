<?php

namespace App\Http\Controllers;

use App\Event;
use App\Log;
use App\Services\LogService;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Json;
use function response;
use function view;

class LogController extends Controller
{
    // controller is ontvangen en doorsturen.
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    /**
     * Display the patient home page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        // TODO: rework queries so they are called when button is pressed.
        $user = Auth::user();
        $events = $this->logService->getEventsOfCurrentUser();

        $result = compact('user','events');
        Json::dump($result);
        // load the logboekBeheren (nog geen index aanwezig)
        return view("log/logboekBeheren", $result);
    }

    /**
     * Display all the logs of the patient.
     *
     * @param User $userID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($userID){
        $user = Auth::user();
        $events = $this->logService->getEventsOfCurrentUser();
        $patient = Auth::user()->patients()->findOrFail($userID);
        $result = compact('user','events','patient');
        Json::dump($result);
        // load the logboekBeheren (nog geen index aanwezig)
        return view("log/logboekBeheren", $result);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|min:2',
            'description'=>'required|min:2',
            'patient_id' => 'required'
        ];
        $customMessages = [
            'title.required' => 'De log heeft geen titel.',
            'title.min' => 'De titel moet minstens 2 karakters lang zijn.',
            'description.required' => 'De log heeft geen beschrijving.',
            'description.min' => 'De titel moet minstens 2 karakters lang zijn.',
            'patient_id.required' => 'Geen patient geselecteerd.'
        ];
        // Validate the log with the above checks.
        $this->validate($request, $rules, $customMessages);
        // create log if it passed validator.
        $this->logService->createLog($request);

        // Return a success message to master page.
        return Json::createJsonResponse("success","De log <b>$request->title</b> is toegevoegd.");
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Log $log
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Log $log)
    {
        // Validate $request
        $rules = [
            'title' => 'required|min:2',
            'description'=>'required|min:2',
            'patient_id' => 'required'
        ];
        $customMessages = [
            'title.required' => 'De log heeft geen titel.',
            'title.min' => 'De titel moet minstens 2 karakters lang zijn.',
            'description.required' => 'De log heeft geen beschrijving.',
            'description.min' => 'De titel moet minstens 2 karakters lang zijn.',
            'patient_id.required' => 'Geen patient geselecteerd.'
        ];
        // Validate the log with the above checks.
        $this->validate($request, $rules, $customMessages);

        // Update the log if it passed the validator.
        $this->logService->updateLog($log, $request);

        // Return a success message to main page (uses helper now).
        return Json::createJsonResponse("success","De log <b>$request->title</b> is aangepast.");
    }

    public function destroy(Log $log)
    {
        $log->delete();
        return response()->json([
            'type' => 'success',
            'text' => "De log <b>$log->name</b> is verwijdert."
        ]);
    }

    // get all logs, and return result in Json
    public function queryLogs($search = "%")
    {
        // get all id's of the user their patients.
        $search = "%" . $search . "%";
        return $this->logService->getFilteredlogsOfCurrentUser($search);

    }
}
