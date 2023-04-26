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
            'enrollment' => ['period'], 
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

        return $soas;

        if($soas)
        {
            $arr = [];
            foreach ($soas as $key => $soa) 
            {
                $arr[$key]['period_id'] = $soa['period_id'];
                $arr[$key]['periodname'] = $soa['periodname'];
                $arr[$key]['periodcode'] = $soa['periodcode'];

                foreach ($soa['ledgers'] as $k => $r) {
                    $arr[$key]['soa'][$k] = $r;
                    switch ($r['type']) {
                        case 'A':
                            $arr[$key]['soa'][$k]['particulars'] = 'ASSESSMENT';
                            $arr[$key]['soa'][$k]['code'] = 'AS #'.$r['source_id'];
                            $arr[$key]['soa'][$k]['debitcredit'] = 'debit';
                            $arr[$key]['soa'][$k]['user'] = $r['user'];
                            break;
                        case 'S':
                            //GET SCHOLARSHIP DETAILS
                            //$sd = $this->sd_model->scholarshipdiscountgrantinfo($r['sourcedoc']);
                            //$arr[$key]['soa'][$k]['particulars'] = $sd[0]->description;
                            $arr[$key]['soa'][$k]['code'] = 'SC #'.$r['source_id'];
                            $arr[$key]['soa'][$k]['debitcredit'] = 'credit';
                            $arr[$key]['soa'][$k]['user'] = $r['user'];
                            break;
                        // case 'D':
                        //     $sd = $this->sd_model->scholarshipdiscountgrantinfo($r['sourcedoc']);
                        //     $arr[$key]['soa'][$k]['particulars'] = $sd[0]->description;
                        //     $arr[$key]['soa'][$k]['code'] = 'DC #'.$r['source_id'];
                        //     $arr[$key]['soa'][$k]['debitcredit'] = 'credit';
                        //     $arr[$key]['soa'][$k]['user'] = $r['user'];
                        //     break;
                        // case 'SA':
                        //     $sa = $this->sa_model->studentadjustmentinfo($r['sourcedoc']);
                        //     $arr[$key]['soa'][$k]['particulars'] = $sa[0]->name;
                        //     $arr[$key]['soa'][$k]['code'] = 'SA #'.$r['source_id'];
                        //     $arr[$key]['soa'][$k]['debitcredit'] = ($sa[0]->type == 1) ? 'credit' : 'debit';
                        //     $arr[$key]['soa'][$k]['user'] = $r['user'];
                        //     break;
                        // case 'R':
                        //     $receiptinfo = $this->model->receiptFeedetails($r['sourcedoc']);
                        //     $particular = '';
                        //     if($receiptinfo){ 
                        //         $particular .= $receiptinfo[0]->feedesc; 
                        //         if($receiptinfo[0]->bank != 0){
                        //             $particular .= ' ['.$receiptinfo[0]->bankname.'- ('.date('m-d-y',strtotime($receiptinfo[0]->deposit_date)).')]';
                        //         }
                        //     }else{
                        //         $particular .= 'RECEIPT';
                        //     }
                        //     $arr[$key]['soa'][$k]['cancelled'] = ($receiptinfo) ? $receiptinfo[0]->cancelled : 0;
                        //     $arr[$key]['soa'][$k]['cancel_remark'] = ($receiptinfo) ? $receiptinfo[0]->cancel_remark : '';
                        //     $arr[$key]['soa'][$k]['particulars'] = $particular;
                        //     $arr[$key]['soa'][$k]['code'] = 'OR #'.$r['sourcedoc'];
                        //     $arr[$key]['soa'][$k]['debitcredit'] = 'credit';
                        //     $arr[$key]['soa'][$k]['user'] = ($receiptinfo) ? ($receiptinfo[0]->name) ? $receiptinfo[0]->name : '' : '';
                        //     break;
                        
                    }
                }
                //usort($arr[$key]['soa'], Others::make_comparer(['ledgerno',SORT_ASC]));
            }

            return $arr;
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