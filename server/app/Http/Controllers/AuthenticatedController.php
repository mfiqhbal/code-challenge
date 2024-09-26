<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\RegisterUserService;

class AuthenticatedController extends Controller
{
    private $userService;

    public function __construct(
        RegisterUserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        try {
            return $this->userService->login($request);
        } catch (Exception $e) {
            return response()->json(['message' => 'Login failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::user()->token()->revoke();

        return $this->success('Logout Successful', $request->all());
    }
}
