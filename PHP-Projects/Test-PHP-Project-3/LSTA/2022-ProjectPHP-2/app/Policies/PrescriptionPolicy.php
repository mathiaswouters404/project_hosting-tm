<?php

namespace App\Policies;

use App\MedicationPatient;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\MedicationPatient  $medicationPatient
     * @return mixed
     */
    public function view(User $user, MedicationPatient $medicationPatient)
    {
        if (($user->id = $medicationPatient->patient_id && $medicationPatient->selfPrescribed) || ($user->isPatientFromUser($medicationPatient->patient_id))) return Response::allow();
        return Response::deny("You can't access this user's prescriptions");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\MedicationPatient  $medicationPatient
     * @return mixed
     */
    public function update(User $user, MedicationPatient $medicationPatient)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\MedicationPatient  $medicationPatient
     * @return mixed
     */
    public function delete(User $user, MedicationPatient $medicationPatient)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\MedicationPatient  $medicationPatient
     * @return mixed
     */
    public function restore(User $user, MedicationPatient $medicationPatient)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\MedicationPatient  $medicationPatient
     * @return mixed
     */
    public function forceDelete(User $user, MedicationPatient $medicationPatient)
    {
        //
    }
}
