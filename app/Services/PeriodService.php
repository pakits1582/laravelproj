<?php

namespace App\Services;

use App\Libs\Helpers;
use App\Models\Period;
use App\Models\SetupPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PeriodService
{
    //

    public function returnPeriods($request, $all = false)
    {
        $query = Period::with('terminfo')->orderBy('year')->orderBy('priority_lvl');

        if($request->has('keyword') && !empty($request->keyword)) {
            $query->where(function($query) use($request){
                $query->where('code', 'like', '%'.$request->keyword.'%')
                ->orWhere('name', 'like', '%'.$request->keyword.'%');
            });
        }
        if($request->has('source') && ($request->source == '1' || $request->source == '2')) {
            $query->where('source', $request->source);
        }

        if($all){
            return $query->get();
        }
    
        return $query->paginate(10);
    }

    public function insertPeriod($request)
    {
        $lastprioritylvl = Period::where('year', $request->validated('year'))->max('priority_lvl');
        $prioritylvl = ($lastprioritylvl) ? $lastprioritylvl + 1 : 1;

        $insert = Period::firstOrCreate(['code' => $request->code, 'term_id' => $request->term, 'year' => $request->year], array_merge($request->validated(), ['priority_lvl' => $prioritylvl]));
        
        return $insert;
    }

    public function saveUserCurrentPeriod($request)
    {
        $validated = $request->validate([
            'period' => 'required',
        ]);

        $period = Period::find($validated['period']);

        SetupPeriod::where('user_id', Auth::id())->delete();

        SetupPeriod::create([
            'user_id' => Auth::id(),
            'period_id' => $validated['period'],
        ]);

        session()->put('current_period', $period->id);
        session()->put('periodname', $period->name);
        session()->put('periodterm', $period->term_id);
    }
}
