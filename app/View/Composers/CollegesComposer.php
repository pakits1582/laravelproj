<?php

namespace App\View\Composers;

use App\Models\College;
use Illuminate\View\View;

class CollegesComposer
{
    public function compose(View $view)
    {
        $view->with('colleges', College::all());
    }
}
