<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function store(Request $request){
            $rules = [
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|string|min:6',
            ];
            $messages = [
                'name.required'         => 'O campo nome é obrigatório.',
                'name.max'              => 'O nome não pode exceder 255 caracteres.',
                'email.required'        => 'O campo e‑mail é obrigatório.',
                'email.email'           => 'Informe um e‑mail válido.',
                'email.unique'          => 'Este e‑mail já está em uso.',
                'password.required'     => 'O campo senha é obrigatório.',
                'password.min'          => 'A senha deve ter ao menos 6 caracteres.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }
    
            $data = $request->only(['name', 'email', 'password']);

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
    
            return response()->json([
                'success' => true,
                'data'    => $user
            ], 201);
    }
}
