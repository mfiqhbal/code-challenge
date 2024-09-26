<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegisterUserService
{

    public function login($request)
    {
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $this->userInfo($request);

            return response()->json(['message' => 'Login successful', 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'Login failed: ' . $e->getMessage()], 500);
        }
    }
    public function getToken($user)
    {
        return $user->createToken('token')->accessToken;
    }

    public function userInfo($request)
    {
        $user = Auth::user();

        $user->update([
            'last_login_at' => now()
        ]);

        if ($request->remember_me == 'true') {
            Passport::personalAccessTokensExpireIn(now()->addWeek());
        }

        $token = $this->getToken($user);

        $user->token = $token;

        return $user;
    }
}
