<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthenticatedController extends Controller
{
    public function loginAdmin(LoginRequest $request)
    {
        $credentials = $request->only('tel', 'password');
        if (!($token = auth()->attempt($credentials))) {
            return $this->unauthorizedRes(__('messages.loginFailed'));
        }

        $user = auth()->user();
        if ($user->role && !in_array($user->role, [UserRoles::Admin, UserRoles::ReportViewer])) {
            auth()->logout();
            return $this->unauthorizedRes('Thông tin đăng nhập không chính xác.');
        }

        return $this->okRes([
            'token' => $token,
        ], __('messages.loginSuccessful'));
    }
}
