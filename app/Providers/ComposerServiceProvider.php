<?php

namespace App\Providers;

use App\View\Composers\CollegesComposer;
use App\View\Composers\ConfigurationComposer;
use App\View\Composers\DepartmentComposer;
use App\View\Composers\EducationallevelComposer;
use App\View\Composers\PeriodComposer;
use App\View\Composers\ProgramComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('partials.educlevels.*', EducationallevelComposer::class);
        View::composer('partials.colleges.*', CollegesComposer::class);
        View::composer('partials.departments.*', DepartmentComposer::class);
        View::composer('partials.programs.*', ProgramComposer::class);
        View::composer('partials.periods.*', PeriodComposer::class);
        View::composer(['layout', 'auth.*'], ConfigurationComposer::class);
    }
}
