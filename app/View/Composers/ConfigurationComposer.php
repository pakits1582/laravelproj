<?php

namespace App\View\Composers;

use App\Models\Configuration;
use Illuminate\View\View;

class ConfigurationComposer
{
    public function compose(View $view)
    {
        $view->with('configuration', Configuration::take(1)->first());
    }
}
