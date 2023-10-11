<?php

namespace App\Services;

use App\Models\FeeSetup;
use App\Models\Enrollment;
use App\Models\PaymentSchedule;


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
            'enrolled_classes.class.curriculumsubject.subjectinfo'
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
                    $fees_payables = $this->processFeesArray($fees_array, $total_subjects, $total_units, $professional_subjects, $academic_subjects, []);

                    return $fees_payables;
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

    public function processFeesArray($fees_array, $total_subjects, $total_units, $professional_subjects, $academic_subjects, $postcharges)
    {
        $uniqueFeeTypes = collect(array_values(array_unique(array_column(array_column($fees_array, 'fee'), 'feetype'), SORT_REGULAR)))->sortBy('order');
        $totaltuition = 0;
        $labfeetotal = 0;
        $miscfeetotal = 0;
        $otherfeetotal = 0;
        $additionalfeetotal = 0;
        $totalfees = 0;
        $allfees = [];

        //return $uniqueFeeTypes;

        if($uniqueFeeTypes && $fees_array)
        {
            foreach ($uniqueFeeTypes as $key => $feetype) 
            {
                $fee_type_id = $feetype['id'];

                $fee_type_fees = array_filter($fees_array, function ($item) use ($fee_type_id) 
                {
                    return isset($item['fee']['fee_type_id']) && $item['fee']['fee_type_id'] === $fee_type_id;
                });

                foreach ($fee_type_fees as $k => $fee) 
                {
                    if(strcasecmp($feetype['type'], 'Tuition Fees') == 0)
                    {
                        $total = 0;
                        //ACADEMIC
                        if(strcasecmp($fee['fee']['name'], 'academic') == 0)
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
      
                                $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $acadsubjtotal);
                                $total += $acadsubjtotal;
                                $totaltuition += $acadsubjtotal;
                
                            }
                        }
                        //PROFESSIONAL
                        if(strcasecmp($fee['fee']['name'], 'professional') == 0)
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

                                $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $profsubjtotal);
                                $total += $profsubjtotal;
                                $totaltuition += $profsubjtotal;
                            }
                        }
                        //TUITION FEE
                        if(strcasecmp($fee['fee']['name'], 'tuition fee') == 0)
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

                                $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $tuitiontotal);
                                $total += $tuitiontotal;
                                $totaltuition += $tuitiontotal;
                            }
                        }
                    }
                }
            }
        }

    }
}
