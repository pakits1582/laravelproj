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
                switch ($user->utype) {
                    case User::TYPE_INSTRUCTOR:
                        $info = 'instructorinfo';
                        break;
                    case User::TYPE_STUDENT:
                        $info = 'studentinfo';
                        break;
                    default:
                         $info = 'info';
                        break;
                }
                $user->load($info, 'access');

                $view->with(['user' => $user, 'info' => ['info' => $info]]);
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
