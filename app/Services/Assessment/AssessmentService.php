<?php

namespace App\Services\Assessment;

use Carbon\Carbon;
use App\Services\FeeService;
use App\Models\Configuration;
use App\Models\PaymentSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\EnrolledClassSchedule;
use App\Services\Enrollment\EnrollmentService;

class AssessmentService
{

    public function updateAssessment($request, $assessment)
    {
        DB::beginTransaction();

        $validated = $request->validate([
            'enrollment_id' => 'required',
            'assessment_id' => 'required',
        ]);    

        $assessment->load(
            'enrollment',
            'enrollment.enrolled_classes.class',
            'enrollment.grade.internalgrades',
        );

        $enrollment = $assessment->enrollment;
        $enrollment->update(['assessed' => 1, 'enrolled_units' => $request->enrolled_units]);

        $assessment->assessed = 1;
        $assessment->amount = $request->totalfees;
        $assessment->user_id = Auth::id();
        $assessment->save();

        $assessment->breakdowns()->delete();
        $assessment->details()->delete();
        $assessment->exam()->delete();

        if($request->filled('fees'))
        {
            $fees = [];
            foreach ($request->fees as $key => $fee) 
            {
                $fee_info = explode("-", $fee);

                $fees[] = [
                    'assessment_id' => $assessment->id,
                    'fee_id' => $fee_info[0],
                    'amount' => $fee_info[1],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $assessment->details()->insert($fees);
        }

        if($request->filled('assessbreakdown'))
        {
            $assessbreakdowns = [];
            foreach ($request->assessbreakdown as $key => $assessbreakdown) 
            {
                $assessbreakdown_info = explode("-", $assessbreakdown);

                $assessbreakdowns[] = [
                    'assessment_id' => $assessment->id,
                    'fee_type_id' => $assessbreakdown_info[0],
                    'amount' => $assessbreakdown_info[1],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $assessment->breakdowns()->insert($assessbreakdowns);
        }

        if($request->filled('exams'))
        {
            $exams = ['downpayment' => $request->downpayment, 'amount' => $request->totalfees];
            $x = 1;
            foreach ($request->exams as $key => $exam) 
            {
                $exams['exam'.$x] = $exam;
                $x++;
            }

            $assessment->exam()->create($exams);
        }

        if($enrollment && $enrollment->validated == 1)
        {
            $this->reenterInternalGrades($enrollment);
            $this->updateStudentledgerAssessment($enrollment, $request->totalfees);
            $this->reenterStudentLedgerDetails($enrollment);
        }

        DB::commit();

        return true;
    }

    public function reenterInternalGrades($enrollment)
    {
        $configuration = Configuration::select('current_period')->first();
        $current_period = $configuration->current_period;
        
        $enrolled_classes = $enrollment->enrolled_classes;
        $internalgrades = $enrollment->grade->internalgrades;

        if($enrolled_classes->isNotEmpty()) 
        {
            $internal_grades = [];

            if($current_period != session('current_period'))
            {
                foreach ($enrolled_classes as $key => $enrolled_class) 
                {
                    $class_id_exists = $internalgrades->contains('class_id', $enrolled_class->class_id);

                    if(!$class_id_exists)
                    {
                        $internal_grades[] = [
                            'grade_id' => $enrollment->grade->id,
                            'class_id' => $enrolled_class->class->id,
                            'units' => $enrolled_class->class->units,
                            'grading_system_id' => NULL,
                            'completion_grade' => NULL,
                            'final' => 0,
                            'user_id' =>  $enrolled_class->user_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                }

                $enrollment->grade->internalgrades()->insert($internal_grades);
            }else{
                foreach ($enrolled_classes as $key => $enrolled_class) 
                {
                    $internal_grades[] = [
                        'grade_id' => $enrollment->grade->id,
                        'class_id' => $enrolled_class->class->id,
                        'units' => $enrolled_class->class->units,
                        'grading_system_id' => NULL,
                        'completion_grade' => NULL,
                        'final' => 0,
                        'user_id' =>  $enrolled_class->user_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                $enrollment->grade->internalgrades()->where('final', 0)->delete();
                $enrollment->grade->internalgrades()->insert($internal_grades);
            }
        }
    }

    public function updateStudentledgerAssessment($enrollment, $totalfee)
    {
        $enrollment->studentledger_assessment()->update(['amount' => $totalfee]);
    }

    public function reenterStudentLedgerDetails($enrollment)
    {
        $studentledger_id = $enrollment->studentledger_assessment->id;
        
        if($enrollment->assessment->details->isNotEmpty()) 
        {
            $studentledger_details = [];
            foreach ($enrollment->assessment->details as $key => $assessment_detail) 
            {
                $studentledger_details[] = [
                    'studentledger_id' => $studentledger_id,
                    'fee_id' => $assessment_detail->fee_id,
                    'amount' =>  $assessment_detail->amount,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            $enrollment->studentledger_assessment->details()->delete();
            $enrollment->studentledger_assessment->details()->insert($studentledger_details);
        }
    }

    public function enrolledClassSchedules($enrollment_id)
    {
        $query = EnrolledClassSchedule::with([
            'class' => [
                'instructor',
                'curriculumsubject.subjectinfo', 
                'sectioninfo'
            ]
        ])->where('enrollment_id', $enrollment_id);

        $enrolled_class_schedules = $query->get();

        $class_schedule_array = [];

        if ($enrolled_class_schedules->isNotEmpty()) 
        {
            foreach ($enrolled_class_schedules as $key => $class_schedule) 
            {
                $class_schedule_array[] = [
                    'class_id' => $class_schedule->class->id,
                    'class_code' => $class_schedule->class->code,
                    'from_time' => $class_schedule->from_time,
                    'to_time' => $class_schedule->to_time,
                    'day' => $class_schedule->day,
                    'room' => $class_schedule->room,
                    'subject_code' => $class_schedule->class->curriculumsubject->subjectinfo->code,
                    'subject_name' => $class_schedule->class->curriculumsubject->subjectinfo->name,
                    'instructor_id' => $class_schedule->class->instructor_id,
                    'instructor_last_name' => $class_schedule->class->instructor->last_name ?? '',
                    'instructor_first_name' => $class_schedule->class->instructor->first_name ?? '',
                    'instructor_middle_name' => $class_schedule->class->instructor->midle_name ?? '',
                    'section_code' => $class_schedule->class->sectioninfo->code,
                ];
            }
        }

        return $class_schedule_array; 
    }

    public function assessmentInformation($assessment)
    {
        $assessment->load(['enrollment.period', 'enrollment.student.user', 'enrollment.program', 'enrollment.curriculum', 'enrollment.section']);
        $configuration = Configuration::take(1)->first();

        $enrolled_classes  = (new EnrollmentService())->enrolledClassSubjects($assessment->enrollment_id);
        $setup_fees        = (new FeeService())->returnSetupFees($assessment->period_id, $assessment->enrollment->program->educational_level_id);
        $payment_schedules = PaymentSchedule::with(['paymentmode'])->where('period_id', session('current_period'))->where('educational_level_id', $assessment->enrollment->program->educational_level_id)->get();
        //POSTCHARGES
        
        //PREVIOUS BALANCE

        return compact('assessment','configuration','enrolled_classes', 'setup_fees', 'payment_schedules');
    }
}