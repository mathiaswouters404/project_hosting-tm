<?php

namespace App\Services;

use App\PatientRight;
use App\PatientCarePersonnel;
use App\User;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientService
{
    /**
     * Creates an association between the patient and the current user (CareTaker or Doctor)
     * Checks if the patient exist and hasn't been added to the user already
     * @param Request $request the submitted post request with the patient code
     * @return Json
     */
    public function importPatient(Request $request) {
        $patientId = $request->code;
        $personnelId = Auth::id();

        $patient = User::find($patientId);

        if ($patient == null) {
            return Json::createJsonResponse('error', 'This is not a valid code!');
        }

        if (
            PatientCarePersonnel::where([
                ['personnel_id', $personnelId],
                ['patient_id', $patientId]
            ])
                ->exists()
        ) {
            return Json::createJsonResponse('error', 'You have already added this patient!');
        } else {
            $this->savePatientCarePersonnel($patientId);
            return Json::createJsonResponse('success', "The patient $patient->firstName $patient->lastName has been added.");
        }
    }

    /**
     * Removes the association between a patient and the current user (CareTaker or Doctor)
     * If the submitted user doesn't exist you will get an error message
     * @param int $patientId the patient id of the patient that must be deleted
     * @return Json
     */
    public function deletePatientPersonnel(int $patientId) {
        $patient = User::find($patientId);

        if ($patient == null) {
            return Json::createJsonResponse('error', 'No patient has been removed, this is not a valid code!');
        } else {
            PatientCarePersonnel::where([
                ['personnel_id', Auth::id()],
                ['patient_id', $patientId]
            ])
                ->delete();

            return Json::createJsonResponse('success', "Removed $patient->firstName $patient->lastName out of your patients list.");
        }
    }

    /**
     * Creates a new PatientCarePersonnel object with the patient id and the current user and saves it to the database
     * @param int $patientId the id of the patient
     * @return void
     */
    private function savePatientCarePersonnel(int $patientId) {
        $patientCarePersonnel = new PatientCarePersonnel();
        $patientCarePersonnel->personnel_id = Auth::id();
        $patientCarePersonnel->patient_id = $patientId;
        $patientCarePersonnel->save();
    }

    /**
     * Changes the patient rights of the user with the given user id
     * @param Request $request the request containing the new right values
     * @param int $patientId the id of the patient
     * @return Json
     */
    public function editRights(Request $request, int $patientId)
    {
        $patient = User::find($patientId);

        if ($patient == null) {
            return Json::createJsonResponse('error', 'No patient with this code has been found!');
        } else {
            // Loops over all post variables
            foreach ($request->all() as $key => $value) {

                // The variable key is a right_id attribute
                if (str_starts_with($key, 'right_id_')) {

                    // We take the right id from the name
                    $rightId = intval(str_replace("right_id_", "", $key));

                    // We update the right in the patient_rights table
                    $this->saveRight($rightId, $value, $patientId);
                }
            }

            return Json::createJsonResponse('success', "The rights of $patient->firstName $patient->lastName have been updated!");
        }
    }

    /**
     * Saves a value for the right with this right name for the user
     * @param int $rightId
     * @param bool $hasRight
     * @param int $patientId
     * @return void
     */
    private function saveRight(int $rightId, bool $hasRight, int $patientId) {
        $right = PatientRight::
            where(
                [
                    ['patient_id', $patientId],
                    ['right_type_id', $rightId]
                ])
            ->get()
            ->first();

        $right->has_right = $hasRight;

        $right->save();
    }
}
