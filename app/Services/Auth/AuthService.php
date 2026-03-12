<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Repositories\Auth\UserRepositoryInterface;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ){}

    public function login(LoginDto $dto, string $ip): void {
        $throttleKey = $this->throttleKey($dto->email, $ip);

//        $this->ensureIsNotRateLimited($throttleKey);

        $authenticated = Auth::attempt(
            credentials: ['email' => $dto->email, 'password' => $dto->password],
            remember:    $dto->remember,
        );

        if (! $authenticated) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha incorretos.',
            ]);
        }

        RateLimiter::clear($throttleKey);
    }

    public function logout(): void {
        Auth::logout();
    }

    public function emailExists(string $email): bool
    {
        return $this->userRepository->exists($email);
    }

    private function throttleKey(string $email, string $ip): string
    {
        return Str::lower($email) . '|' . $ip;
    }
}
