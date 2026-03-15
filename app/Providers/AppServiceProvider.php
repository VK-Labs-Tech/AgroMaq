<?php

namespace App\Providers;

use App\Repositories\Contracts\DiarioBordoRepositoryInterface;
use App\Repositories\Eloquent\DiarioBordoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DiarioBordoRepositoryInterface::class, DiarioBordoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
