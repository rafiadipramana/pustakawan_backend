<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    /**
     * Show authenticated user
     */
    public function index()
    {
        return response()->json([
            'success' => 'true',
            'data' => [
                'title' => 'Test API',
                'author' => 'Rafi',
                'email' => '',
            ],
            'message' => 'Test Server Lokal',
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'string',
            'email' => 'email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->username == null && $request->email == null) {
            return response()->json([
                'success' => 'false',
                'message' => 'Username or email is required',
            ], 400);
        }

        $input = $request->all();
        if (!$token = auth('api')->attempt($input)) {
            return response()->json([
                'success' => 'false',
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'success' => 'true',
            'message' => 'User logged in successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
            ]
        ], 200);
    }


    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|regex:/^\S*$/u',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response()->json([
            'success' => 'true',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user->name,
            ]
        ], 201);
    }

    /**
     * Refresh a token
     */
    public function refresh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $token = auth('api')->refresh;

        return response()->json([
            'success' => 'true',
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
            ]
        ], 200);
    }
}
