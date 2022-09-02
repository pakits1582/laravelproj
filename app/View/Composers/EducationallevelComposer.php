<?php

namespace App\View\Composers;

use App\Models\Educationallevel;
use Illuminate\View\View;

class EducationallevelComposer
{
    public function compose(View $view)
    {
        $view->with('educlevels', Educationallevel::all());
    }
}