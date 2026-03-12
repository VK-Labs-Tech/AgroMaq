<?php

namespace App\Providers;

use App\Repositories\Auth\UserRepository;
use App\Repositories\Auth\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(
            abstract: UserRepositoryInterface::class, concrete: UserRepository::class
        );
    }

    public function boot(): void
    {

    }
}
