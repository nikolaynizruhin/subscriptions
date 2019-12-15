<?php

namespace App\Http\Controllers;

use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'password.confirm']);
    }

    /**
     * Update user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|max:255|password:api',
            'new_password' => 'required|string|min:8|max:255|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->new_password),
            'api_token' => $token = Str::random(60),
        ]);

        return ['token' => $token, 'user' => new User($request->user())];
    }
}
