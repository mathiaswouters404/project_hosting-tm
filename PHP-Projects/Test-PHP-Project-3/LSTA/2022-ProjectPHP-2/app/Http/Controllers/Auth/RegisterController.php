<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PatientRight;
use App\Providers\RouteServiceProvider;
use App\RightType;
use App\Role;
use App\Services\RegisterService;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Json;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $registerService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegisterService $registerService)
    {
        $this->middleware('guest');
        $this->registerService = $registerService;
    }

    /**
     * Get a validator for an incoming registration request.
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'role_id' => ['required'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * @return mixed
     */
    protected function create(array $data)
    {
        $user = $this->registerService->createUser($data);

        $this->registerService->createUserRights($user, $data);

        return $user;
    }

    /**
     * Returns the register form and makes 2 arrays of data
     * One array with all the possible roles
     * One array with all the possible right types
     * These arrays are used to build a dynamic form in the front end for setting these values
     * @return Application|Factory|View
     */
    protected function showRegistrationForm() {
        return view('auth.register', $this->registerService->getRegistrationFormData());
    }
}
