<?php

namespace App\Providers;

use App\Repositories\ApplicationRepository;
use Illuminate\Support\Facades\Schema;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Repositories\Contracts\InternshipRepositoryInterface;
use App\Repositories\InternshipRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InternshipRepositoryInterface::class, InternshipRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
