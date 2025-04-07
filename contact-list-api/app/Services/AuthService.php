<?php

namespace App\Services;

use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function register(array $data)
    {
        $user = $this->userRepository->create($data);

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function login(array $data)
    {
        if (! Auth::attempt($data)) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = Auth::user();

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }
}
