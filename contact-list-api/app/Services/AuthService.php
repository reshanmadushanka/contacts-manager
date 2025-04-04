<?php

namespace App\Services;

use App\Repositories\Interfaces\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function  register(array $data)
    {
        $user = $this->userRepository->create($data);

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ];
    }

    public function login(array $data)
    {
        $user = $this->userRepository->userByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ];
    }
}
