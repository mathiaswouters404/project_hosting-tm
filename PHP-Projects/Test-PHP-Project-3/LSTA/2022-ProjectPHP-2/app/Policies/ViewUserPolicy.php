<?php

namespace App\Policies;

use App\User;
use Auth;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ViewUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     * @return Response
     */
    public function view(User $user, User $model)
    {
        $role = $user->role->name;
        if (
            ($user->id == $model->id) ||
            (
                ($role == "Dokter" || $role == "Mantelzorger") &&
                ($user->isPatientFromUser($model->id))
            )
        ) {
            return Response::allow();
        } else {
            return Response::deny("You can't view this user's data!");
        }
    }

    public function createAppointment(User $user, User $model) {
        $role = $user->role->name;
        if (
            ($user->id == $model->id) ||
            (
                ($role == "Mantelzorger") &&
                ($user->isPatientFromUser($model->id))
            )
        ) {
            return Response::allow();
        } else {
            return Response::deny("You can't create an appointment for this user!");
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
