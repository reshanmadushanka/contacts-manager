<?php

namespace App\Repositories\Users;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function userByEmail(string $email): ?User;
}
