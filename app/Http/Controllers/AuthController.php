<?php

namespace App\Http\Controllers;

use App\Utils\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! auth()->attempt($credentials)) {
            return response()->json(new JsonResponse(
                'Login Gagal',
                [],
                'login_error'
            ), Response::HTTP_UNAUTHORIZED);
        }

        $token = auth()->user()->createToken('API Token')->plainTextToken;

        return response()->json(new JsonResponse(
            'Login berhasil',
            [
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('sanctum.expiration'),
            ]
        ), Response::HTTP_OK);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(new JsonResponse(
            'Me',
            auth()->user()
        ), Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(new JsonResponse(
            'Logout Berhasil',
            []
        ), Response::HTTP_OK);
    }
}
