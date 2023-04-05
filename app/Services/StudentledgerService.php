<?php

namespace App\Services;

use App\Models\User;
use App\Models\Studentledger;
use App\Models\StudentledgerDetail;
use Database\Seeders\StudentSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentledgerService
{

    public function getAllStatementOfAccounts($student_id, $period_id="")
    {
        $ledgers = Studentledger::with(['enrollment' => ['period'], 'user' => ['info', 'instructorinfo', 'studentinfo']]);

        if (!empty($period_id)) {
            $ledgers->whereHas('enrollment', function ($query) use ($period_id, $student_id) {
                $query->where('period_id', $period_id)
                ->where('enrollments.student_id', $student_id);
            });
        }

        $allSOA = $ledgers->get();

        //return $allSOA;
        $soa = [];
        if($allSOA)
        {
            foreach ($allSOA as $key => $v) {
                $soa_period = $v->enrollment->period->id;
                if (!isset($soa[$soa_period])) {
                    $soa[$soa_period] = [
                        'period_id'  => $soa_period,
                        'periodcode' => $v->enrollment->period->code,
                        'periodname' => $v->enrollment->period->name,
                        'ledgers'    => []
                    ];
                }

                switch ($v->user->utype) {
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

                $soa[$soa_period]['ledgers'][] = array(
                    'id'         => $v['id'],
                    'source_id'  => $v['source_id'],
                    'type'       => $v['type'],
                    'amount'     => $v['amount'],
                    'user'       => $v->user->{ $info }->name,
                    'created_at' => $v['created_at']
                );
            }
        }

        return array_values($soa);
    }

    public function returnStatementOfAccounts($student_id, $period_id)
    {
        $soas = $this->getAllStatementOfAccounts($student_id, $period_id);

        return $soas;
    }

    public function getAllPaidLedgerDetails($enrollment_id)
    {
        $query = StudentledgerDetail::query();
        $query->select(
            'studentledger_details.fee_id', 
            'studentledger_details.amount',
            'fees.code',
            'fees.name',
            'fee_types.type',
            'fee_types.order'
        );
        $query->Join('studentledgers', 'studentledger_details.studentledger_id', 'studentledgers.id');
        $query->Join('fees', 'studentledger_details.fee_id', 'fees.id');
        $query->Join('fee_types', 'fees.fee_type_id', 'fee_types.id');

        $query->where('studentledgers.enrollment_id', $enrollment_id);
        $query->where('studentledgers.type', '!=', 'A');

        return $query->get();
    }

    public function insertStudentledgerDetails($ledgerno, $enrollment, $totaldeduction)
    {
        $defaultFee = (new FeeService())->getDefaultFee();
        $allPayableAssessmentDetails = $enrollment->assessment->details;
        $allPaidLedgerDetails = $this->getAllPaidLedgerDetails($enrollment->id);


        return $allPayableAssessmentDetails;
        
    }
}