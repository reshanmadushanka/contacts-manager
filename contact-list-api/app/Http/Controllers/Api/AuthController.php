<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginValidationRequest;
use App\Http\Requests\UserValidationRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Register a new user.
     */
    public function register(UserValidationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = $this->authService->register($data);

            return ApiResponse::success($result, 'User registered successfully', 201);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to register user', 500, [$e->getMessage()]);
        }
    }

    /**
     * Login a user.
     */
    public function login(UserLoginValidationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->authService->login($validated);

            return ApiResponse::success($result, 'Login successful');
        } catch (Throwable $e) {
            return ApiResponse::error('Login failed', 401, [$e->getMessage()]);
        }
    }

    /**
     * Logout a user.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();

            return ApiResponse::success([], 'Logged out successfully');
        } catch (Throwable $e) {
            return ApiResponse::error('Logout failed', 500, [$e->getMessage()]);
        }
    }
}
