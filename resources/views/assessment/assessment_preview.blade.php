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
                        <tr class="label">
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


    <div class="row">
        <h6 class="mx-3 font-weight-bold text-black">Assessment of Fees:</h6>
    </div>

    <div class="row">
        <div class="col-md-6">
        <h6 class="mx-3 font-weight-bold text-black">Assessment of Fees:</h6>
        </div>
        <div class="col-md-6">

        </div>
    </div>
</div>


