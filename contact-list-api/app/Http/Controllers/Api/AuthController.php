<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginValidationRequest;
use App\Http\Requests\UserValidationRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function register(UserValidationRequest $request): JsonResponse
    {
        $data = $request->validated();
        return response()->json($this->authService->register($data), 201);
    }

    public function login(UserLoginValidationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        return response()->json($this->authService->login($validated));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
