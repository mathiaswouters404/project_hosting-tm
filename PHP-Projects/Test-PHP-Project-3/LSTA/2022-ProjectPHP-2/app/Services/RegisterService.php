<?php

namespace App\Services;

use App\PatientRight;
use App\RightType;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    /**
     * Creates a new user and saves it to the database
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data) {
        $newUser = [
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'role_id' => $data['role_id'],
            'admin' => isset($data['admin']),
            'status' => isset($data['status']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        return User::create($newUser);
    }

    /**
     * Creates a record for each possible right type in the database for the user
     * @param User $user the user you want to create the right types for
     * @return void
     */
    public function createUserRights(User $user, array $data) {
        $rightTypes = RightType::get();

        foreach ($rightTypes as $rightType) {
            $right = [
                'patient_id' => $user->id,
                'right_type_id' => $rightType->id,
                'has_right' => isset($data['right_id_' . $rightType->id])
            ];

            PatientRight::create($right);
        }
    }

    /**
     * Returns all data used to build the registration form
     * @return array
     */
    public function getRegistrationFormData(): array
    {
        $roles = Role::orderBy('id')
            ->get()
            ->makeHidden(['created_at', 'updated_at']);
        $rightTypes = RightType::allRightsWithDisplayName();

        return compact('roles', 'rightTypes');
    }
}
