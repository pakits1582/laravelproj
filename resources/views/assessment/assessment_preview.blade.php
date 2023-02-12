<div>
    <!-- Page Heading -->
    @php
        $professional_subjects = $enrolled_classes->where('class.curriculumsubject.subjectinfo.professional', 1)->sum('class.tfunits');
        $academic_subjects = $enrolled_classes->where('class.curriculumsubject.subjectinfo.professional', 0)->sum('class.tfunits');
        $total_subjects = $enrolled_classes->count();
        $total_units = $enrolled_classes->sum('class.tfunits');
        $laboratory_subjects  = [];
        $all_subjects  = [];

    @endphp

    <div class="row mb-2">
        <div>
            
        </div>
        <div class="col-md-12">
            <h6 class="mid m-0 font-weight-bold text-black">{{ $configuration->name }}</h6>
            <h6 class="mid m-0 font-weight-bold text-black">{{ $configuration->address }}</h6>
            <h6 class="mid m-0 font-weight-bold text-black">Assessment ({{ $assessment->enrollment->period->name }})</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <label for="term" class="m-0 font-weight-bold text-primary">ID No.</label>
        </div>
        <div class="col-md-4">
            <div class="text-black">{{ $assessment->enrollment->student->user->idno }}</div>
        </div>
        <div class="col-md-2">
            <label for="term" class="m-0 font-weight-bold text-primary">Program & Year</label>
        </div>
        <div class="col-md-2">
            <div class="text-black">{{ $assessment->enrollment->program->code }}-{{ $assessment->enrollment->year_level }}</div>

        </div>
        <div class="col-md-2">
            <label for="term" class="m-0 font-weight-bold text-primary">Assessment No.</label>
        </div>
        <div class="col-md-1">
            <div class="text-black">{{ $assessment->id }}</div>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-1">
            <label for="term" class="m-0 font-weight-bold text-primary">Name</label>
        </div>
        <div class="col-md-4">
            <div class="text-black">{{ $assessment->enrollment->student->last_name }}, {{ $assessment->enrollment->student->first_name }} {{ $assessment->enrollment->student->name_suffix }} {{ $assessment->enrollment->student->middle_name }}</div>
        </div>
        <div class="col-md-2">
            <label for="term" class="m-0 font-weight-bold text-primary">Section</label>
        </div>
        <div class="col-md-2">
            <div class="text-black">{{ $assessment->enrollment->section->code }}</div>
        </div>
        <div class="col-md-2">
            <label for="term" class="m-0 font-weight-bold text-primary">Enrollment No.</label>
        </div>
        <div class="col-md-1">
            <div class="text-black">{{ $assessment->enrollment->id }}</div>
        </div>
    </div>
    <div class="table-responsive-sm">
        <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
            <thead class="">
                <tr>
                    <th class="w50">Code</th>
                    <th class="w120 mid">Subject</th>
                    <th>Description</th>
                    <th class="w40 mid">Units</th>
                    <th class="w35 mid">Lec</th>
                    <th class="w35 mid">Lab</th>
                    <th class="w300 mid">Schedule</th>
                    <th class="">Section</th>
                </tr>
            </thead>
            <tbody class="text-black" id="return_enrolled_subjects">
                @if (count($enrolled_classes) > 0)
                    @php
                        $totalunits = 0;
                    @endphp
                    @foreach ($enrolled_classes as $enrolled_class)
                        @php
                            $row_color = '';
                            if($enrolled_class->class->dissolved === 1)
                            {
                                $row_color = 'dissolved';
                            }elseif ($enrolled_class->class->tutorial === 1) {
                                $row_color = 'tutorial';
                            }elseif ($enrolled_class->class->f2f === 1) {
                                $row_color = 'f2f';
                            }
                        @endphp
                        <tr class="label {{ $row_color }}">
                            <td class="">{{ $enrolled_class->class->code }}</td>
                            <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->code }}</td>
                            <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->name }}</td>
                            <td class="mid">{{ ($enrolled_class->class->isprof === 1) ? '('.$enrolled_class->class->units.')' : $enrolled_class->class->units }}</td>
                            <td class="mid">{{ $enrolled_class->class->lecunits }}</td>
                            <td class="mid">{{ $enrolled_class->class->labunits }}</td>
                            <td class="">{{ $enrolled_class->class->schedule->schedule }}</td>
                            <td class="">{{ $enrolled_class->class->sectioninfo->code }}</td>
                        </tr>
                        @php
                            $totalunits += $enrolled_class->class->units;
                            $all_subjects[] = $enrolled_class->class->curriculumsubject->subjectinfo->id;

                            if($enrolled_class->class->curriculumsubject->subjectinfo->laboratory == 1)
                            {
                                $laboratory_subjects[] = $enrolled_class->class->curriculumsubject->subjectinfo->id;
                            }
                        @endphp
                    @endforeach
                    <tr class="nohover">
                        <td colspan="3"><h6 class="m-0 font-weight-bold text-primary">Total Subjects ({{ count($enrolled_classes) }})</h6></td>
                        <td colspan="5"><h6 class="m-0 font-weight-bold text-primary">(<span id="enrolledunits">{{ $totalunits }}</span>) Total Units </h6>
                        </td>
                    </tr>
                @else
                    <tr class="">
                        <td class="mid" colspan="8">No records to be displayed</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @php
        $idno            = $assessment->enrollment->student->user->idno;
        $userid          = $assessment->enrollment->student->user->id;
        $enrollment_id   = $assessment->enrollment->id;
        $assessment_id   = $assessment->id;
        $educational_level_id  = $assessment->enrollment->program->educational_level_id;
        $college_id      = $assessment->enrollment->program->college_id;
        $program_id      = $assessment->enrollment->program->id;
        $year_level      = $assessment->enrollment->year_level;
        $new             = $assessment->enrollment->new;
        $old             = $assessment->enrollment->old;
        $transferee      = $assessment->enrollment->transferee;
        $cross_enrollee  = $assessment->enrollment->cross_enrollee;
        $foreigner       = $assessment->enrollment->foreigner;
        $returnee        = $assessment->enrollment->returnee;
        $sex             = $assessment->enrollment->student->sex;
        $enrollment_date = $assessment->enrollment->created_at;
        $validated       = $assessment->enrollment->validated;
        $feesArray = [];

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
                    $feesArray[] = $fee;
                }
            }
        }

        $uniqueFeeTypes = collect(array_values(array_unique(array_column(array_column($feesArray, 'fee'), 'feetype'), SORT_REGULAR)))->sortBy('order');
        $totaltuition = 0;
        $labfeetotal = 0;
        $miscfeetotal = 0;
        $otherfeetotal = 0;
        $additionalfeetotal = 0;
        $totalfees = 0;
    @endphp

    <div class="row">
        <h6 class="mx-3 font-weight-bold text-black">Assessment of Fees:</h6> 
    </div>

    <div class="row">
        <div class="col-md-6">
            @if ($uniqueFeeTypes)
                @foreach ($uniqueFeeTypes->toArray() as $key => $feetype)
                    <h6 class="mx-3 font-weight-bold text-black">{{ $feetype['type'] }}</h6>

                    @foreach ($feesArray as $k => $fee)
                        
                    @endforeach
                @endforeach
            @endif
        </div>
        <div class="col-md-6">

        </div>
    </div>
</div>


