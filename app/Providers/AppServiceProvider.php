<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            try {
                $user = User::findOrfail(Auth::id());
                $user->load('info', 'access');

                $view->with('user', $user);
            } catch (\Exception $e) {
                Log::error(get_called_class(), [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                ]);
            }
        });

        Paginator::useBootstrap();
    }
}
