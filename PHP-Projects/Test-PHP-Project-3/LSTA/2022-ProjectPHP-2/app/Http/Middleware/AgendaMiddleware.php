<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class AgendaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');
        $user = Auth::user();

        if ($id == Auth::id()) {
            return $next($request);
        } else {
            $roleName = $user->role->name;

            if ($roleName == "Dokter" || $roleName == "Mantelzorger") {
                if ($user->isPatientFromUser($id)) {
                    return $next($request);
                }
            }
        }

        abort(403, "You do not have access to this user's agenda!");
    }
}
