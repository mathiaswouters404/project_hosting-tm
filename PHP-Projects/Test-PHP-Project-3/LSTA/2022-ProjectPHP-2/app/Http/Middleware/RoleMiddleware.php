<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if(auth()->user() == null) {
            return abort(403, "You have to be logged in and authorized to access this page");
        }

        $userRole = auth()->user()->role->name;
        $userAdmin = auth()->user()->admin;
        $errorMessage = "";

        $rolesSize = count($roles);

        for($i = 0; $i < $rolesSize; $i++) {

            $role = $roles[$i];

            if($role == $userRole) {
                return $next($request);
            }
            else if($role == "Admin") {
                if($userAdmin) {
                    return $next($request);
                }
            }

            //build up string for error message

            if($i != 0 && $i != $rolesSize - 1) {
                $errorMessage .= ", ";
            } else if($i == $rolesSize - 1 && $rolesSize > 1) {
                $errorMessage .= " or ";
            }
            $errorMessage .= $role;
        }
        return abort(403, "Only users with role ${errorMessage} can access this page");
    }
}
