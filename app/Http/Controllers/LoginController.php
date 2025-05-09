<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ], [
        'email.required' => 'O campo e‑mail é obrigatório.',
        'email.email' => 'Informe um e‑mail válido.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.min' => 'A senha deve ter ao menos 6 caracteres.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais inválidas'
            ], 401);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao gerar token'
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => auth()->user()->email
        ]
    ]);
}
}
