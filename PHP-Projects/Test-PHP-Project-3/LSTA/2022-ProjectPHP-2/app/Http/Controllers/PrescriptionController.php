<?php

namespace App\Http\Controllers;

use App\MedicationPatient;
use App\Services\PrescriptionService;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Json;

class PrescriptionController extends Controller
{

    private $prescriptionService;

    public function __construct(PrescriptionService $prescriptionService) {
        $this->prescriptionService = $prescriptionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('prescription.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doctorIndex($id)
    {
        $patient = Auth::user()->patients()->findOrFail($id);
        $this->authorize('view', $patient);
        $patient->name = $patient->firstName . ' ' . $patient->lastName;
        $patient->makeHidden(['lastName', 'firstName', 'email', 'status', 'admin', 'email_verified_at', 'role_id', 'created_at', 'updated_at', 'pivot']);
        $result = compact('patient');
        Json::dump($result);
        return view('prescription.index', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('prescription');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate $request
        $this->validate($request, [
            'medication_id' => 'required|exists:medications,id',
            'patient_id' => 'required|exists:users,id',
            'selfPrescribed' => 'required',
            'dosage' => 'required',
            'reason' => 'required|min:3',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date'
        ]);

        $patient = User::find($request->patient_id);
        $this->authorize('view', $patient);


        return $this->prescriptionService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MedicationPatient  $medicationPatient
     * @return \Illuminate\Http\Response
     */
    public function show(MedicationPatient $medicationPatient)
    {
        return redirect('prescription');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MedicationPatient  $medicationPatient
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicationPatient $medicationPatient)
    {
        return redirect('prescription');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MedicationPatient  $medicationPatient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicationPatient $medicationPatient)
    {
        //if (auth::id() != $request->patient_id) return response('Unauthorized', 401);

        // validate $request
        $this->validate($request, [
            'medication_id' => 'required|exists:medications,id',
            'patient_id' => 'required|exists:users,id',
            'selfPrescribed' => 'required',
            'dosage' => 'required',
            'reason' => 'required|min:3',
            'startDate' => 'required|date',

        ]);

        //$patient = User::find($request->patient_id);
        $this->authorize('view', $medicationPatient);


        return $this->prescriptionService->update($request, $medicationPatient);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MedicationPatient  $medicationPatient
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicationPatient $medicationPatient)
    {
        //$patient = $medicationPatient->patient;
        $this->authorize('view', $medicationPatient);
        return $this->prescriptionService->destroy($medicationPatient);
    }

    public function query($id = null)
    {
        if($id) {
            $patient = user::find($id);
        }
        else {
            $patient = auth::user();
        }
        $this->authorize('view', $patient);
        return $this->prescriptionService->query($id);
    }

}
