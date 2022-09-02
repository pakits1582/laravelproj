<?php

namespace App\View\Composers;

use App\Models\Educationallevel;
use App\Models\Program;
use Illuminate\View\View;

class ProgramComposer
{
    public function compose(View $view)
    {
        $view->with('programs', Program::where('active', 1)->orderBy('code')->get());
    }
}