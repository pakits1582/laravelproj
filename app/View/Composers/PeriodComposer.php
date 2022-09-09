<?php

namespace App\View\Composers;

use App\Models\Period;
use Illuminate\View\View;

class PeriodComposer
{
    public function compose(View $view)
    {
        $view->with('periods', Period::where('source', 1)
                                    ->where('display', 1)
                                    // ->whereNot(function ($query) {
                                    //     $query->where('id', session('current_period'));
                                    // })
                                    ->orderBy('year', 'DESC')
                                    ->get());
    }
}
