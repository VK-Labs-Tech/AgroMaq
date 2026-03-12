<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function exists(string $email): bool;
}
