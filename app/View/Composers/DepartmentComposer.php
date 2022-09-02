<?php

namespace App\View\Composers;

use App\Models\Department;
use Illuminate\View\View;

class DepartmentComposer
{
    public function compose(View $view)
    {
        $view->with('departments', Department::all());
    }
}