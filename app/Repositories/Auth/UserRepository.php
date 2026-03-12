<?php

namespace App\Repositories\Auth;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function exists(string $email): bool
    {
        return User::where('email', $email)->exists();
    }
}
