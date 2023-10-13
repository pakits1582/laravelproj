<?php

namespace App\Services;

use App\Jobs\Reassessment;
use Carbon\Carbon;
use App\Models\FeeSetup;
use App\Models\Enrollment;
use App\Models\PaymentSchedule;
use Illuminate\Support\Facades\DB;


class ReassessmentService
{
    public function enrolledstudents($period_id, $educational_level_id = '', $college_id = '', $program_id = '', $year_level = '')
    {
        $query = Enrollment::where('period_id', $period_id)->where('acctok', 1)->where('assessed', 1);

        $query->when(isset($program_id) && !empty($program_id), function ($query) use($program_id) {
            $query->where('program_id', $program_id);
        });

        $query->when(isset($year_level) && !empty($year_level), function ($query) use($year_level) {
            $query->where('year_level', $year_level);
        });

        $query->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use($educational_level_id) {
            $query->whereHas('program.level', function($query) use($educational_level_id){
                $query->where('educational_level_id', $educational_level_id);
            });
        });

        $query->when(isset($college_id) && !empty($college_id), function ($query) use($college_id) {
            $query->whereHas('program.level', function($query) use($college_id){
                $query->where('college_id', $college_id);
            });
        });

        return $query->get();
    }

    public function reassessEnrollments($period_id, $educational_level_id = '', $college_id = '', $program_id = '', $year_level = '')
    {
        $setup_fees        = FeeSetup::with('fee.feetype')->where('period_id', $period_id)->get();
        $payment_schedules = PaymentSchedule::with('paymentmode')->where('period_id', $period_id)->get();

        $perPage = 1000;
        $all_enrollments = collect();

        Enrollment::with([
            'student',
            'student.user:id,idno',
            'program',
            'program.level',
            'enrolled_classes.class.curriculumsubject.subjectinfo',
            'grade.internalgrades',
            'studentledger_assessment'
        ])
        ->where('period_id', $period_id)
        ->where('assessed', 1)
        ->where('validated', 1)
        ->withCount('enrolled_classes')
        ->when(isset($program_id) && !empty($program_id), function ($query) use ($program_id) {
            $query->where('program_id', $program_id);
        })
        ->when(isset($year_level) && !empty($year_level), function ($query) use ($year_level) {
            $query->where('year_level', $year_level);
        })
        ->when(isset($educational_level_id) && !empty($educational_level_id), function ($query) use ($educational_level_id) {
            $query->whereHas('program.level', function ($query) use ($educational_level_id) {
                $query->where('educational_level_id', $educational_level_id);
            });
        })
        ->when(isset($college_id) && !empty($college_id), function ($query) use ($college_id) {
            $query->whereHas('program.level', function ($query) use ($college_id) {
                $query->where('college_id', $college_id);
            });
        })
        ->chunk($perPage, function ($enrollments) use (&$all_enrollments) {
            $all_enrollments = $all_enrollments->concat($enrollments);
        });

        return $this->processReassessments($all_enrollments, $setup_fees, $payment_schedules);
    }
    
    public function processReassessments($enrollments, $setup_fees, $payment_schedules)
    {
        $chunked_enrollments = $enrollments->chunk(500);

        foreach ($chunked_enrollments as $chunked_enrollment) 
        {
            if (!empty($chunked_enrollment)) 
            {
                foreach ($chunked_enrollment as $key => $enrollment) 
                {
                    $setup_fees = $setup_fees->where('educational_level_id', $enrollment->program->educational_level_id);
                    $payment_schedules = $payment_schedules->where('educational_level_id', $enrollment->program->educational_level_id);
                    //GET POST CHARGES

                    $professional_subjects = $enrollment->enrolled_classes->where('class.curriculumsubject.subjectinfo.professional', 1)->sum('class.tfunits');
                    $academic_subjects = $enrollment->enrolled_classes->where('class.curriculumsubject.subjectinfo.professional', 0)->sum('class.tfunits');
                    $total_subjects = $enrollment->enrolled_classes->count();
                    $total_units = $enrollment->enrolled_classes->sum('class.tfunits');
                    $laboratory_subjects  = [];
                    $all_subjects  = [];
                    $total_units = 0;

                    foreach ($enrollment->enrolled_classes as $key => $enrolled_class) 
                    {
                        $total_units += $enrolled_class->class->units;
                        $all_subjects[] = $enrolled_class->class->curriculumsubject->subjectinfo->id;
                        if($enrolled_class->class->curriculumsubject->subjectinfo->laboratory == 1)
                        {
                            $laboratory_subjects[] = $enrolled_class->class->curriculumsubject->subjectinfo->id;
                        }                    
                    }

                    $fees_array = $this->processAssessmentFees($enrollment, $setup_fees, $laboratory_subjects, $all_subjects);
                    $fees_payables = $this->processFeesArray($enrollment, $fees_array, $total_subjects, $total_units, $professional_subjects, $academic_subjects, $laboratory_subjects, []);
                    $assessment_exams = $this->processPaymentSchedules($enrollment, $payment_schedules, $fees_payables);
                    
                    $forinsert = $this->processFeesForInsert(
                        $enrollment,
                        $total_units,
                        $fees_payables['total_fees'], 
                        $fees_payables['assessment_details_array'], 
                        $fees_payables['assessment_breakdowns_array'],
                        $assessment_exams
                    );

                    return $forinsert;
                }
            }
        }
    }

    public function processAssessmentFees($enrollment, $setup_fees, $laboratory_subjects, $all_subjects)
    {
        $educational_level_id  = $enrollment->program->educational_level_id;
        $college_id      = $enrollment->program->college_id;
        $program_id      = $enrollment->program->id;
        $year_level      = $enrollment->year_level;
        $new             = $enrollment->new;
        $old             = $enrollment->old;
        $transferee      = $enrollment->transferee;
        $cross_enrollee  = $enrollment->cross_enrollee;
        $foreigner       = $enrollment->foreigner;
        $returnee        = $enrollment->returnee;
        $sex             = $enrollment->student->sex;
        $fees_array = [];

        if(count($setup_fees) > 0)
        {
            foreach ($setup_fees->toArray() as $key => $fee) 
            {
                $passed = 1;  
                
                if($fee['educational_level_id'] != NULL && $passed == 1) {
                    $passed = ($educational_level_id == $fee['educational_level_id']) ? 1 : 0;
                }

                if($fee['college_id'] != NULL && $passed == 1){
                    $passed = ($college_id == $fee['college_id']) ? 1 : 0;
                }

                if($fee['program_id'] != NULL && $passed == 1){
                    $passed = ($program_id == $fee['program_id']) ? 1 : 0;
                }

                if($fee['year_level'] != NULL && $passed == 1){
                    $passed = ($year_level == $fee['year_level']) ? 1 : 0;
                }

                if($fee['new'] != 0 && $passed == 1){
                    $passed = ($new == $fee['new']) ? 1 : 0;
                }

                if($fee['old'] != 0 && $passed == 1){
                    $passed = ($old == $fee['old']) ? 1 : 0;
                }

                if($fee['transferee'] != 0 && $passed == 1){
                    $passed = ($transferee == $fee['transferee']) ? 1 : 0;
                }

                if($fee['cross_enrollee'] != 0 && $passed == 1){
                    $passed = ($cross_enrollee == $fee['cross_enrollee']) ? 1 : 0;
                }

                if($fee['foreigner'] != 0 && $passed == 1){
                    $passed = ($foreigner == $fee['foreigner']) ? 1 : 0;
                }

                if($fee['returnee'] != 0 && $passed == 1){
                    $passed = ($returnee == $fee['returnee']) ? 1 : 0;
                }

                if($fee['sex'] != NULL && $passed == 1){
                    $passed = ($sex == $fee['sex']) ? 1 : 0;
                }

                if($fee['subject_id'] != NULL){
                    $passed = in_array($fee['subject_id'], $laboratory_subjects) ? 1 : 0;
                }

                if($fee['subject_id'] != NULL){
                    $passed = in_array($fee['subject_id'], $all_subjects) ? 1 : 0;
                }

                if($passed == 1)
                {
                    $fees_array[] = $fee;
                }
            }
        }
        
        return $fees_array;
    }

    public function processFeesArray($enrollment, $fees_array, $total_subjects, $total_units, $professional_subjects, $academic_subjects, $laboratory_subjects, $postcharges)
    {
        $unique_fee_types = collect(array_values(array_unique(array_column(array_column($fees_array, 'fee'), 'feetype'), SORT_REGULAR)))->sortBy('order');
        $total_tuition = 0;
        $lab_fee_total = 0;
        $misc_fee_total = 0;
        $other_fee_total = 0;
        $additional_fee_total = 0;
        $total_fees = 0;
        $assessment_details_array = [];
        $assessment_breakdowns_array = [];

        //return $uniqueFeeTypes;

        if($unique_fee_types && $fees_array)
        {
            foreach ($unique_fee_types as $key => $feetype) 
            {
                $fee_type_id = $feetype['id'];
                $total = 0;

                $fee_type_fees = array_filter($fees_array, function ($item) use ($fee_type_id) 
                {
                    return isset($item['fee']['fee_type_id']) && $item['fee']['fee_type_id'] === $fee_type_id;
                });
                
                foreach ($fee_type_fees as $k => $fee) 
                {
                    $fee_name = strtolower($fee['fee']['name']);

                    if(strcasecmp($feetype['type'], 'Tuition Fees') == 0)
                    {
                        
                        //ACADEMIC
                        if(strcasecmp($fee_name, 'academic') == 0)
                        { 
                            if($academic_subjects != 0)
                            {
                                switch ($fee['payment_scheme']) {
                                    case 1: //fixed
                                        $acadsubjtotal = $fee['rate'];
                                        break;
                                    case 2: //per units
                                        $acadsubjtotal = $academic_subjects*$fee['rate'];
                                        break;
                                    case 3: //per subject
                                        $acadsubjtotal = $total_subjects*$fee['rate'];
                                        break;
                                    default:
                                        $acadsubjtotal = $fee['rate'];
                                        break;
                                }
      
                                $assessment_details_array[] = ['assessment_id' => $enrollment->assessment->id, 'fee_id' => $fee['fee_id'], 'fee_name' => $fee_name, 'amount' => $acadsubjtotal];
                                $total += $acadsubjtotal;
                                $total_tuition += $acadsubjtotal;
                
                            }
                        }
                        //PROFESSIONAL
                        if(strcasecmp($fee_name, 'professional') == 0)
                        {
                            if($professional_subjects != 0)
                            {
                                switch ($fee['payment_scheme']) {
                                    case 1: //fixed
                                        $profsubjtotal = $fee['rate'];
                                        break;
                                    case 2: //per units
                                        $profsubjtotal = $professional_subjects*$fee['rate'];
                                        break;
                                    case 3: //per subject
                                        $profsubjtotal = $total_subjects*$fee['rate'];
                                        break;
                                    default:
                                        $profsubjtotal = $fee['rate'];
                                        break;
                                }

                                $assessment_details_array[] = ['assessment_id' => $enrollment->assessment->id, 'fee_id' => $fee['fee_id'], 'fee_name' => $fee_name, 'amount' => $profsubjtotal];
                                $total += $profsubjtotal;
                                $total_tuition += $profsubjtotal;
                            }
                        }
                        //TUITION FEE
                        if(strcasecmp($fee_name, 'tuition fee') == 0)
                        {
                            if($total_units != 0)
                            {
                                switch ($fee['payment_scheme']) {
                                    case 1: //fixed
                                        $tuitiontotal = $fee['rate'];
                                        break;
                                    case 2: //per units
                                        $tuitiontotal = $total_units*$fee['rate'];
                                        break;
                                    case 3: //per subject
                                        $tuitiontotal = $total_subjects*$fee['rate'];
                                        break;
                                    default:
                                        $tuitiontotal = $fee['rate'];
                                        break;
                                }

                                $assessment_details_array[] = ['assessment_id' => $enrollment->assessment->id, 'fee_id' => $fee['fee_id'], 'fee_name' => $fee_name, 'amount' => $tuitiontotal];

                                $total += $tuitiontotal;
                                $total_tuition += $tuitiontotal;
                            }
                        }
                    }else{
                        $rate = 0;

                        switch ($fee['payment_scheme']) 
                        {
                            case 1: //fixed
                                $rate = $fee['rate'];
                                break;
                            case 2: //per units
                                $rate = $total_units*$fee['rate'];
                                break;
                            case 3: //per subject
                                $rate = $total_subjects*$fee['rate'];
                                break;
                            default:
                                $rate = $fee['rate'];
                                break;
                        }
                        if(strcasecmp($feetype['type'], 'Miscellaneous Fees') == 0) {
                            $misc_fee_total += $rate;
                        }
                        if(strcasecmp($feetype['type'], 'Other Miscellaneous Fees') == 0) {
                            $other_fee_total += $rate;
                        }
                        if(strcasecmp($feetype['type'], 'Additional Fees') == 0) {
                            $additional_fee_total += $rate;
                        }
                        if($fee['subject_id'] != 0 && strcasecmp($feetype['type'], 'Laboratory Fees') == 0)
                        {
                            if(in_array($fee['subject_id'], $laboratory_subjects) === true)
                            {
                                $lab_fee_total += $rate;
                            }
                        }

                        $assessment_details_array[] = ['assessment_id' => $enrollment->assessment->id, 'fee_id' => $fee['fee_id'], 'fee_name' => $fee_name, 'amount' => $rate];
                        $total += $rate;
                    }
                }

                $total_fees += $total;
                $assessment_breakdowns_array[] = ['assessment_id' => $enrollment->assessment->id, 'fee_type_id' => $fee_type_id, 'fee_type_name' => $feetype['type'], 'amount' => $total];
            }
        }

        return [
            'total_tuition' => $total_tuition,
            'lab_fee_total' => $lab_fee_total,
            'misc_fee_total' => $misc_fee_total,
            'other_fee_total' => $other_fee_total,
            'additional_fee_total' => $additional_fee_total,
            'total_fees' => $total_fees,
            'assessment_details_array' => $assessment_details_array,
            'assessment_breakdowns_array' => $assessment_breakdowns_array
        ];
    }

    public function processPaymentSchedules($enrollment, $payment_schedules, $fees_payables)
    {
        $total_tuition = $fees_payables['total_tuition'];
        $lab_fee_total = $fees_payables['lab_fee_total'];
        $misc_fee_total = $fees_payables['misc_fee_total'];
        $other_fee_total = $fees_payables['other_fee_total'];
        $additional_fee_total = $fees_payables['additional_fee_total'];
        $total_fees = $fees_payables['total_fees'];

        $educational_level_id =  $enrollment->program->educational_level_id;
        $year_level = $enrollment->year_level;

        $paymentsched = [];
        $paypassed = 0;

        if($payment_schedules->toArray())
        {
            foreach ($payment_schedules->toArray() as $key => $payment_schedule) 
            {
                if($payment_schedule['educational_level_id'] == NULL && $payment_schedule['year_level'] == NULL){
                    $paypassed = 1;
                }else{
                    if($payment_schedule['educational_level_id'] != NULL){
                        $paypassed = ($educational_level_id == $payment_schedule['educational_level_id']) ? 1 : 0;
                    }
                    if($payment_schedule['year_level'] != 0){
                        $paypassed = ($year_level == $payment_schedule['year_level']) ? 1 : 0;
                    }
                }
                if($paypassed == 1)
                {
                    $paymentsched[] = $payment_schedule;
                }
            }
        }
        
        $fixedtuition = 0;
        $fixedmisc = 0;
        $fixedother = 0;
        $downpayment = 0;
        $exams = [];

        foreach ($paymentsched as $key => $ps) 
        {
            $topay = 0;

            $fixedtuition = ($ps['payment_type'] == 2) ? $ps['tuition'] : 0;
            $fixedmisc    = ($ps['payment_type'] == 2) ? $ps['miscellaneous'] : 0;
            $fixedother   = ($ps['payment_type'] == 2) ? $ps['others'] : 0;

            $totaltuition  = $total_tuition-$fixedtuition;
            $miscfeetotal  = $misc_fee_total-$fixedmisc;
            $otherfeetotal = $other_fee_total-$fixedother;

            $tuitioncomp = ($ps['payment_type'] == 1) ? ($ps['tuition']/100) * $totaltuition : $ps['tuition'];
            $misccomp    = ($ps['payment_type'] == 1) ? ($ps['miscellaneous']/100) * $miscfeetotal : $ps['miscellaneous'];
            $otherscomp  = ($ps['payment_type'] == 1) ? ($ps['others']/100) * $otherfeetotal : $ps['others'];

            if(strcasecmp($ps['description'], 'downpayment') == 0){
                $topay = $tuitioncomp+$misccomp+$otherscomp+$lab_fee_total+$additional_fee_total;
                $downpayment = $topay;
            }else{
                $topay = $tuitioncomp+$misccomp+$otherscomp;
                $exams[] = array('amount' => $topay);
            }
        }
        
        $assessment_exams = ['downpayment' => $downpayment, 'amount' => $total_fees];

        if($exams)
        {
            $x = 1;
            foreach ($exams as $key => $exam) 
            {
                $assessment_exams['exam'.$x] = $exam['amount'];
                $x++;
            }
        }

        return $assessment_exams;
    }

    public function processFeesForInsert(
        $enrollment, 
        $total_units, 
        $total_fees, 
        $assessment_details,
        $assessment_breakdowns, 
        $assessment_exams
    )
    {
        Reassessment::dispatch(
            $enrollment, 
            $total_units, 
            $total_fees, 
            $assessment_details,
            $assessment_breakdowns, 
            $assessment_exams
        );
    }
}
