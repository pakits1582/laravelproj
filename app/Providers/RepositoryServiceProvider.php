<?php

namespace App\Providers;

use App\Repositories\SchoolRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\SchoolRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SchoolRepositoryInterface::class, SchoolRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
