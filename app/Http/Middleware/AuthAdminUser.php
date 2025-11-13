<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;


class AuthAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == UserRoles::Admin && Auth::user()->status == UserStatus::Active) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, __('messages.unauthorizedAccess'));
    }
}
