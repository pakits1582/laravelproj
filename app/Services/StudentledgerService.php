<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Studentledger;
use App\Models\PaymentSchedule;
use App\Models\Studentadjustment;
use Illuminate\Support\Facades\DB;
use App\Models\StudentledgerDetail;
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
            'studentadjustment_info',
            'receipt_info' => ['fee', 'student', 'bank']
        ]);

        $ledgers->whereHas('enrollment', function ($query) use ($period_id, $student_id) {
            $query->where('enrollments.student_id', $student_id);

            if (!empty($period_id)) {
                $query->where('period_id', $period_id);
            }
        });

        $allSOA = $ledgers->get();

        $soa = [];
        if($allSOA)
        {
            foreach ($allSOA as $key => $v) {
                $soa_period = $v->enrollment->period->id;
                if (!isset($soa[$soa_period])) {
                    $soa[$soa_period] = [
                        'period_id'  => $soa_period,
                        'period_year' => $v->enrollment->period->year,
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

        usort($soa, function ($a, $b) {
            return $a['period_year'] <=> $b['period_year'];
        });

        return array_values($soa);
    }

    public function returnStatementOfAccounts($soas, $period_id = NULL, $operator = '==')
    {
        if ($period_id != NULL) {
            $soas = array_filter($soas, function ($soa) use ($period_id, $operator) {
                switch ($operator) {
                    case '==':
                        return $soa['period_id'] == $period_id;
                    case '!=':
                        return $soa['period_id'] != $period_id;
                    default:
                        return true;
                }
            });
        }

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
                        case 'R':
                            $particular = '';
                            $particular .= $ledger['ledger_info']['fee']['name'];
                            $particular .= ($ledger['ledger_info']['bank_id']) ? ' ['.$ledger['ledger_info']['bank']['name'].' - ('.Carbon::parse($ledger['ledger_info']['deposit_date'])->format('m-d-y').')]' : '';
                            $newLedger['cancelled'] = $ledger['ledger_info']['cancelled'];
                            $newLedger['cancel_remark'] = $ledger['ledger_info']['cancel_remark'];
                            $newLedger['particular'] = $particular;
                            $newLedger['code'] = 'OR #'.$ledger['source_id'];
                            $newLedger['debitcredit'] = 'credit';
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

        return $feesArraytoledgerdetails;
    }

    public function returnPreviousBalanceRefund($soas, $period_id)
    {
        $previous_period_soas = array_filter($soas, function ($soa) use($period_id) {
            return $soa['period_id'] != $period_id;
        });

        return $this->soaBalance($previous_period_soas);
    }

    public function soaBalance($soas)
    {
        $soasBalance = [];

        if ($soas) 
        {
            foreach ($soas as $soa) 
            {
                $debit = 0;
                $credit = 0;
                foreach ($soa['ledgers'] as $ledger) 
                {
                    $amount = $ledger['amount'];
                    $type = $ledger['type'];
                    $cancelled = $ledger['ledger_info']['cancelled'] ?? 0;
                    $credit += ($amount < 0 && $type == 'R' && $cancelled == 0) ? $amount : 0;
                    $credit += ($amount < 0 && $type != 'R') ? $amount : 0;
                    $debit += ($amount >= 0) ? $amount : 0;
                }
                $balance = $debit + $credit;
                $soasBalance[] = [
                    'period_id' => $soa['period_id'],
                    'period_code' => $soa['period_code'],
                    'period_name' => $soa['period_name'],
                    'balance' => $balance,
                    'debit' => $debit,
                    'credit' => $credit,
                ];
            }
        }
        
        return $soasBalance;
    }

    public function returnPaymentSchedules($soas, $period_id, $educational_level_id, $enrollment)
    {
        $payment_schedules = PaymentSchedule::with(['paymentmode'])->where('period_id', $period_id)->where('educational_level_id', $educational_level_id)->get();

        if($enrollment == 'false')
        {
            return 'false';
        }

        $debit = 0;
        $credit = 0;

        if ($soas) 
        {
            foreach ($soas as $soa) 
            {
                foreach ($soa['ledgers'] as $ledger) 
                {
                    $amount = $ledger['amount'];
                    $type = $ledger['type'];
                    $cancelled = $ledger['ledger_info']['cancelled'] ?? 0;
                    $credit += ($amount < 0 && $type == 'R' && $cancelled == 0) ? $amount : 0;
                    $credit += ($amount < 0 && $type != 'R') ? $amount : 0;
                    $debit += ($amount >= 0 && $type != 'A') ? $amount : 0;
                }
            }
        }

        return ['payment_schedules' => $payment_schedules, 'assessment_exam' => $enrollment['assessment']['exam'], 'debit' => $debit, 'credit' => $credit];
    }

    public function computePaymentSchedule($student_id, $period_id, $pay_period, $enrollment)
    {
        if($enrollment == 'false')
        {
            return 'false';
        }

        $payment_schedules = PaymentSchedule::with(['paymentmode'])->where('period_id', $period_id)->where('educational_level_id', $enrollment['program']['educational_level_id'])->get();
        $default_pay_period = (Auth::user()->paymentperiod) ? ((Auth::user()->paymentperiod->pay_period > max(array_keys($payment_schedules->toArray()))) ? max(array_keys($payment_schedules->toArray())) : Auth::user()->paymentperiod->pay_period) : 0;

        $soas = $this->getAllStatementOfAccounts($student_id, $period_id);
        $debit = 0;
        $credit = 0;

        if ($soas) 
        {
            foreach ($soas as $soa) 
            {
                foreach ($soa['ledgers'] as $ledger) 
                {
                    $amount = $ledger['amount'];
                    $type = $ledger['type'];
                    $cancelled = $ledger['ledger_info']['cancelled'] ?? 0;
                    $credit += ($amount < 0 && $type == 'R' && $cancelled == 0) ? $amount : 0;
                    $credit += ($amount < 0 && $type != 'R') ? $amount : 0;
                    $debit += ($amount >= 0 && $type != 'A') ? $amount : 0;
                }
            }
        }

        $assessment_exam = $enrollment['assessment']['exam'];
        $balance_due = 0;

        if(max(array_keys($payment_schedules->toArray())) ==  $pay_period)
        {   
            $total_payables = $assessment_exam['amount']+$debit;
            $balance_due = $total_payables-str_replace('-', "", $credit);
        }else{
            $rembal = 0;

            for($i=0; $i <= $pay_period; $i++)
            {
                if($i == 0)
                {
                    $rembal += $assessment_exam['downpayment'];
                }else{
                    $exam = 'exam'.$i;
                    $rembal += $assessment_exam[$exam];
                }
            }

            $balance = ($rembal+$debit)-str_replace('-', "", $credit);

            $balance_due = ($balance < 0) ? 0 : $balance;
        }

        return ['balance_due' => number_format($balance_due,2), 'default_pay_period' => $default_pay_period];
    }

    public function saveForwardedBalance($request)
    {
        
        DB::beginTransaction();

        $student = Student::with(['user'])->where('id', $request->student_id)->first();

        if(!$student)
        {
            return [
                'success' => false,
                'message' => 'Something went wrong! Student can not be found!',
                'alert' => 'alert-danger'
            ];
        }

        $soas = $this->getAllStatementOfAccounts($request->student_id,'');
        $soas_balances = $this->soaBalance($soas);

        $period_to = $request->period_to;
        $soa_period_to = array_values(array_filter($soas_balances, function ($soa) use($period_to) {
            return $soa['period_id'] == $period_to;
        }));
        $period_to_enrollment = Enrollment::with(
            [
                'assessment' => [
                    'breakdowns' => ['fee_type'],
                    'details'
                ]
            ])->where('student_id', $request->student_id)->where('period_id', $period_to)->first();


        $period_from = $request->period_from;
        $soa_period_from = array_values(array_filter($soas_balances, function ($soa) use($period_from) {
            return $soa['period_id'] == $period_from;
        }));

        $period_from = $request->period_from;
        $soa_period_from = array_values(array_filter($soas_balances, function ($soa) use($period_from) {
            return $soa['period_id'] == $period_from;
        }));

        $period_from_enrollment = Enrollment::with(
            [
                'assessment' => [
                    'breakdowns' => ['fee_type'],
                    'details'
                ]
            ])->where('student_id', $request->student_id)->where('period_id', $period_from)->first();

        if(!$soa_period_to || !$soa_period_from || !$period_to_enrollment || !$period_from_enrollment)
        {
            return [
                'success' => false,
                'message' => 'Something went wrong! Can not perform requested action!',
                'alert' => 'alert-danger'
            ];
        }

        if($request->balance < 0)
        {
            //PERIOD FROM
            $particular_from = 'DEBIT ADJUSTMENT - Balance forwarded to period '.$soa_period_to[0]['period_name'];
            $particular_to = 'CREDIT ADJUSTMENT - Balance forwarded from period '.$soa_period_from[0]['period_name'];

            $debit_PostData = [
                'enrollment_id' => $period_from_enrollment->id,
                'type' => Studentledger::TYPE_DEBIT,
                'particular' => $particular_from,
                'amount' => str_replace('-', "", str_replace(',', "", $request->balance)),
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $insert_debitPostData = Studentadjustment::firstOrCreate($debit_PostData, $debit_PostData);
            
            $debit_ledgerData = [
                'enrollment_id' => $period_from_enrollment->id,
                'source_id' => $insert_debitPostData->id,
                'type' => 'SA',
                'amount' => str_replace('-', "", str_replace(',', "", $request->balance)),
                'user_id' => Auth::user()->id
            ];

            $debit_studentledger = Studentledger::firstOrCreate($debit_ledgerData, $debit_ledgerData);

            //PERIOD TO
            $credit_PostData = [
                'enrollment_id' => $period_to_enrollment->id,
                'type' => Studentledger::TYPE_CREDIT,
                'particular' => $particular_to,
                'amount' => str_replace('-', "", str_replace(',', "", $request->balance)),
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $insert_creditPostData = Studentadjustment::firstOrCreate($credit_PostData, $credit_PostData);

            $credit_ledgerData = [
                'enrollment_id' => $period_to_enrollment->id,
                'source_id' => $insert_creditPostData->id,
                'type' => 'SA',
                'amount' => str_replace(',', "", $request->balance),
                'user_id' => Auth::user()->id
            ];

            $credit_studentledger = Studentledger::firstOrCreate($credit_ledgerData, $credit_ledgerData);
            $credit_ledgerDetails = (new StudentledgerService())->insertCreditStudentledgerDetails($credit_studentledger, $period_to_enrollment, $request->balance);

        }else if($request->balance > 0){
    
            $particular_from = 'CREDIT ADJUSTMENT - Balance forwarded to period '.$soa_period_to[0]['period_name'];
            $particular_to = 'DEBIT ADJUSTMENT - Balance forwarded from period '.$soa_period_from[0]['period_name'];

            //PERIOD FROM (CREDIT)
            $credit_PostData = [
                'enrollment_id' => $period_from_enrollment->id,
                'type' => Studentledger::TYPE_CREDIT,
                'particular' => $particular_from,
                'amount' => str_replace('-', "", str_replace(',', "", $request->balance)),
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $insert_creditPostData = Studentadjustment::firstOrCreate($credit_PostData, $credit_PostData);

            $credit_ledgerData = [
                'enrollment_id' => $period_from_enrollment->id,
                'source_id' => $insert_creditPostData->id,
                'type' => 'SA',
                'amount' => '-'.$request->balance,
                'user_id' => Auth::user()->id
            ];

            $credit_studentledger = Studentledger::firstOrCreate($credit_ledgerData, $credit_ledgerData);
            $ledgerDetails = (new StudentledgerService())->insertCreditStudentledgerDetails($credit_studentledger, $period_from_enrollment, $request->balance);
            
            //PERIOD TO (DEBIT)
            $debit_PostData = [
                'enrollment_id' => $period_to_enrollment->id,
                'type' => Studentledger::TYPE_DEBIT,
                'particular' => $particular_to,
                'amount' => str_replace('-', "", str_replace(',', "", $request->balance)),
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $insert_debitPostData = Studentadjustment::firstOrCreate($debit_PostData, $debit_PostData);

            $debit_ledgerData = [
                'enrollment_id' => $period_to_enrollment->id,
                'source_id' => $insert_debitPostData->id,
                'type' => 'SA',
                'amount' => $request->balance,
                'user_id' => Auth::user()->id
            ];

            $debit_studentledger = Studentledger::firstOrCreate($debit_ledgerData, $debit_ledgerData);
        }

        DB::commit();

        return [
            'success' => true,
            'message' => 'Balance successfully forwarded!',
            'alert' => 'alert-success'
        ];
    }
}