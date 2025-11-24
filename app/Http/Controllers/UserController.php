<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\UpdateUserPassRequest as AdminUpdateUserPassRequest;
use App\Http\Requests\admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUser()
    {
        return $this->okRes(UserResource::make(auth()->user()));
    }

    public function updateUser(UpdateUserRequest $request)
    {
        auth()->user()->update([
            'name' => $request->name,
            'tel' => $request->tel,
        ]);

        return $this->okRes();
    }

    public function updateUserPass(AdminUpdateUserPassRequest $request)
    {
        auth()->user()->update([
            'password' => $request->password,
        ]);

        return $this->okRes();
    }
}
