<?php

namespace App\Http\Controllers;

use App\Log;
use App\PatientRight;
use App\RightType;
use App\Role;
use App\Services\UserService;
use Auth;
use Facades\App\Helpers\Json;
use Illuminate\Foundation\Auth\User;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Http\Request;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index() {
        $users = Log::get();
        dd($users);
    }

    public function getPatients(): JsonResponse
    {
        return Response()->Json(
            $this->userService->getPatients()
        );
    }

    public function edit(){
        $user = Auth::user();
        $result = compact('user');
        return view('auth.edit',$result);
    }

    public function update(Request $request, User $user)
    {
        // validate $request
        $this->validate($request, [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'email' => 'email:rfc'
        ]);

        $user->lastName = $request->lastName;
        $user->firstName = $request->firstName;
        $user->email = $request->email;
        $user->password = Auth::user()->getAuthPassword();
        $user->profile_picture = $request->profilePicture;
        $user->save();

        return response()->json([
            'type' => 'success',
            'text' => "Your profile is updated"
        ]);



    }

    public function queryLogs($search = "%")
    {
        // get all id's of the user their patients.
        $patientsIds = $this->logService->getPatientUsers();
        $search = "%" . $search . "%";

        $logs = Log::orderBy('Date')->orderBy('created_at')->orderBy('title')
            // join user table.
            ->with("patient")

            // Join event table.
            ->with("event")
            // get only the patients from the logged in user.
            ->wherein("patient_id", $patientsIds)
            // get all id's that return from the query.
            ->whereIn("id", function($subQuery) use($search) {
                // Selects id's from logs.
                $subQuery->select("id")
                    ->from("logs")
                    // Results that correspond to the title.
                    ->where('title', 'like', $search)
                    // OR results that correspond to the description.
                    ->orWhere("description", "like", $search);
            })
            ->get()
            ->transform(function ($item, $key){
                $item->patient->fullName = $item->patient->firstName . " " . $item->patient->lastName;
                return $item;
            });
        return $logs;

    }

    public function queryCanConfirmEvents() {
        return response()->json(Auth::user()->hasRight("complete_tasks"));
    }
}
