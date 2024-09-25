<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.'

        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['access_token' => $token]);
    }

    public function logout()
    {
        $logout = JWTAuth::invalidate(JWTAuth::getToken());

        if ($logout) {
            return response()->json(['success' => true, 'message' => 'Successfully logged out']);
        }
    }
}
