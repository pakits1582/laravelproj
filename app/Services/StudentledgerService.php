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
        $ledgers = Studentledger::with([
            'enrollment' => ['period', 'program'], 
            'user' => ['info', 'instructorinfo', 'studentinfo'],
            'scdc_info' => ['scholarshipdiscount'],
            'assessment_info',
            'studentadjustment_info'
        ]);

        $ledgers->whereHas('enrollment', function ($query) use ($period_id, $student_id) {
            $query->where('enrollments.student_id', $student_id);

            if (!empty($period_id)) {
                $query->where('period_id', $period_id);
            }
        });

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
                        'period_code' => $v->enrollment->period->code,
                        'period_name' => $v->enrollment->period->name,
                        'program_code' => $v->enrollment->program->code,
                        'program_name' => $v->enrollment->program->name,
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

                switch ($v->type) {
                    case 'A':
                        $ledger_info = 'assessment_info';
                        break;
                    case 'S':
                        $ledger_info = 'scdc_info';
                        break;
                    case 'D':
                        $ledger_info = 'scdc_info';
                        break;
                    case 'SA':
                        $ledger_info = 'studentadjustment_info';
                        break;
                    case 'R':
                        $ledger_info = 'receipt_info';
                        break;
                }

                $soa[$soa_period]['ledgers'][] = array(
                    'id'         => $v['id'],
                    'source_id'  => $v['source_id'],
                    'type'       => $v['type'],
                    'amount'     => $v['amount'],
                    'user'       => $v->user->{ $info }->name,
                    'created_at' => $v['created_at'],
                    'ledger_info' => $v->{$ledger_info}
                );
            }
        }

        return array_values($soa);
    }

    public function returnStatementOfAccounts($student_id, $period_id)
    {
        $soas = $this->getAllStatementOfAccounts($student_id, $period_id);

        //return $soas;

        if($soas)
        {
            return array_map(function ($soa) {
                $newSoa = [
                    'period_id' => $soa['period_id'],
                    'period_name' => $soa['period_name'],
                    'period_code' => $soa['period_code'],
                    'program_code' => $soa['program_code'],
                    'program_name' => $soa['program_name'],
                    'soa' => [],
                ];
            
                foreach ($soa['ledgers'] as $ledger) {
                    $newLedger = [
                        'id' => $ledger['id'],
                        'type' => $ledger['type'],
                        'amount' => $ledger['amount'],
                        'user' => $ledger['user'],
                        'created_at' => $ledger['created_at'],
                    ];
            
                    switch ($ledger['type']) {
                        case 'A':
                            $newLedger['particular'] = 'ASSESSMENT';
                            $newLedger['code'] = 'AS #'.$ledger['source_id'];
                            $newLedger['debitcredit'] = 'debit';
                            break;
                        case 'S':
                        case 'D':
                            $newLedger['particular'] = $ledger['ledger_info']['scholarshipdiscount']['description'];
                            $newLedger['code'] = ($ledger['type'] == 'S' ? 'SC' : 'DC').' #'.$ledger['source_id'];
                            $newLedger['debitcredit'] = 'credit';
                            break;
                        case 'SA':
                            $newLedger['particular'] = $ledger['ledger_info']['particular'];
                            $newLedger['code'] = 'SA #'.$ledger['source_id'];
                            $newLedger['debitcredit'] = ($ledger['ledger_info']['type'] == 1) ? 'credit' : 'debit';
                            break;
                    }
            
                    $newSoa['soa'][] = $newLedger;
                }
            
                return $newSoa;
                
            }, $soas);
        }


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

    public function insertCreditStudentledgerDetails($studentledger, $enrollment, $totaldeduction)
    {
        $defaultFee = (new FeeService())->getDefaultFee();
        $allPayableAssessmentDetails = $enrollment->assessment->details->toArray();
        $allPaidLedgerDetails = $this->getAllPaidLedgerDetails($enrollment->id)->toArray();

        $feesArraytoledgerdetails = [];
        $result = [];

        if($allPaidLedgerDetails)
        {
            foreach($allPaidLedgerDetails as $paidfee)
            {
                if(!isset($result[$paidfee['fee_id']]))
                {
                    $result[$paidfee['fee_id']] = $paidfee;  // instantiate temporary city-keyed result array (avoid Notices)
                }else{
                    (float) $result[$paidfee['fee_id']]['amount'] += (float) $paidfee['amount'];  // add current value to previous value
                }
            }
        }
    
        $paidledgerdetails = array_values($result);  // remove temporary keys

        $feesArray = [];

        if($allPayableAssessmentDetails)
        {
            foreach($allPayableAssessmentDetails as $key => $payablefee)
            {
                $a = array_search($payablefee['fee_id'], array_column($paidledgerdetails, 'fee_id'));
                if($a !== false)
                {
                    $bal = $payablefee['amount'] - str_replace('-', "", $paidledgerdetails[$a]['amount']);
                    
                    if(str_replace('-', "", $paidledgerdetails[$a]['amount']) < $payablefee['amount'])
                    {
                        $feesArray[] = array('fee_id' => $payablefee['fee_id'], 'amount' => $bal);
                    }
                }else{
                    $feesArray[] = array('fee_id' => $payablefee['fee_id'], 'amount' => $payablefee['amount']);
                }
            }
        }

        if(empty($feesArray))
        {
            $feesArraytoledgerdetails[] = array('fee_id' => $defaultFee[0]->id, 'amount' => '-'.$totaldeduction);
        }else{
            $diff = 0;

            foreach($feesArray as $key => $value)
            {
                $feeamount = sprintf('%.9F',abs($value['amount']));
                $totaldeduction = sprintf('%.9F',abs($totaldeduction));  
                 
                if($key == 0)
                {
                    $amount = ($feeamount >= $totaldeduction) ? $totaldeduction : $feeamount;
                    $diff = bcsub($totaldeduction, $amount,2);   
                    $feesArraytoledgerdetails[] = array('fee_id' => $value['fee_id'], 'amount' => '-'.str_replace(',', "", $amount));
                }else{
                    if($diff >= $feeamount)
                    {
                        $diff = bcsub($diff, $feeamount,2);
                        $feesArraytoledgerdetails[] = array('fee_id' => $value['fee_id'], 'amount' => '-'.str_replace(',', "", $feeamount));
                    
                    }else if($diff < $feeamount && $diff > 0)
                    {
                        $feesArraytoledgerdetails[] = array('fee_id' => $value['fee_id'], 'amount' => '-'.$diff);
                        $diff = bcsub($diff, $feeamount,2);
                    }
                }
            }

            if($diff > 0)
            {
                $feesArraytoledgerdetails[] = array('fee_id' => $defaultFee[0]->id, 'amount' => '-'.$diff);
            }
        }//END OF IFELSE EMPTY FEESARRAY

        if($feesArraytoledgerdetails)
        {
            $studentledgerdetailsArray = [];
            foreach ($feesArraytoledgerdetails as $key => $value)
            {
                $studentledgerdetailsArray[] = [
                    'studentledger_id' => $studentledger->id, 
                    'fee_id' => $value['fee_id'], 
                    'amount' => $value['amount'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            $studentledger->details()->insert($studentledgerdetailsArray);
        }

        return true;
        
    }
}