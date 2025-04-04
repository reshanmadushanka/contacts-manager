<?php

namespace App\Repositories\Interfaces\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function userByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
