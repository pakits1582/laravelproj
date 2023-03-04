<?php

namespace App\Services;

use App\Models\Studentledger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentledgerService
{

    public function getAllStatementOfAccounts($student_id, $period_id="")
    {
        $query = Studentledger::with(['enrollment' => ['period']])
                ->leftjoin('enrollments', 'studentledgers.enrollment_id', '=', 'enrollments.id')
                ->where('enrollments.student_id', $student_id);
        
        if($period_id)
        {
            $query->where('enrollments.period_id', $period_id);
        }
                
        return $query->get();       
    }

    public function returnStatementOfAccounts($student_id, $period_id)
    {
        $soas = $this->getAllStatementOfAccounts($student_id, $period_id);

        return $soas;
    }
}