<?php

namespace App\Http\Controllers;

use App\Services\PatientService;
use App\User;
use Facades\App\Helpers\Json;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PatientsController extends Controller
{
    private $_patientsService;

    public function __construct() {
        $this->_patientsService = new PatientService();
    }

    /**
     * Returns the home view of the careTaker or the doctor
     * @return Application|Factory|View
     */
    public function index() {
        return view('patients.index');
    }

    /**
     * Stores the association between a patient and the current user (CareTaker or Doctor) in the database
     * @param Request $request the request with the patient code
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        return response()->json($this->_patientsService->importPatient($request));
    }

    /**
     * Unsubscribes the patient with this patient id from the current user
     * @param int $patientId the id of the patient that must be removed
     * @return JsonResponse
     */
    public function destroy(int $patientId): JsonResponse
    {
        return response()->json($this->_patientsService->deletePatientPersonnel($patientId));
    }

    /**
     * Returns a list of all the patients that are associated with the current user
     * @return Collection
     */
    public function queryPatients(): Collection
    {
        return Auth::user()->patients;
    }

    /**
     * Returns a list of the rights the patient has so the edit rights form can be filled out correctly
     * @param int $patientId
     * @return Collection
     */
    public function queryPatientRights(int $patientId): Collection{
        return User::find($patientId)->patientRights()->get()->makeHidden(['id', 'patient_id', 'created_at', 'updated_at']);
    }

    /**
     * Edits the rights of the patient with the given patientId
     * @param Request $request the new values for the patient rights
     * @param int $patientId the id of the patient
     * @return JsonResponse
     */
    public function editRights(Request $request, int $patientId): JsonResponse
    {
        return response()->json($this->_patientsService->editRights($request, $patientId));
    }
}
