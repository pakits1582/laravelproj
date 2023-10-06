<div class="mb-3">
    <div class="row">
        <div class="col-md-1">
            <label for="" class="m-0 font-weight-bold text-primary">Code</label>
        </div>
        <div class="col-md-5">
            <div class="m-0 font-weight-bold text-black">{{ $class->code }} ({{ $class->sectioninfo->code }})</div>
        </div>
        <div class="col-md-1">        
            <label for="" class="m-0 font-weight-bold text-primary">Instructor</label>
        </div>
        <div class="col-md-4">
            <div class="m-0 font-weight-bold text-black">{{ $class->instructor->name ?? '' }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <label for="" class="m-0 font-weight-bold text-primary">Subject</label>
        </div>
        <div class="col-md-5">
            <div class="m-0 font-weight-bold text-black">
                {{ $class->curriculumsubject->subjectinfo->code }} - {{ $class->curriculumsubject->subjectinfo->name }} ({{ $class->units }} UNITS)
            </div>
        </div>
        <div class="col-md-1">
            <label for="" class="m-0 font-weight-bold text-primary">Schedule</label>
        </div>
        <div class="col-md-5">
            <div class="m-0 font-weight-bold text-black">{{ $class->schedule->schedule ?? '' }}</div>
        </div>
    </div>
</div>
<table id="scrollable_table_classinfo" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th>#</th>
            <th class="">ID No.</th>
            <th class="">Name</th>
            <th class="">Program</th>
            <th class="">Year Level</th>
            <th class="">Code</th>
            <th class="w50">Transfer</th>
        </tr>
    </thead>
    <tbody>
        @if (count($enrolled_students))
            @foreach ($enrolled_students as $enrolled_student)
                <tr class="label">
                    <td class="">{{ $loop->iteration }}</td>
                    <td class="">{{ $enrolled_student->enrollment->student->user->idno }}</td>
                    <td class="">{{ ($enrolled_student->enrollment->validated == 0) ? '*' : '' }}{{ $enrolled_student->enrollment->student->name }}</td>
                    <td class="">{{ $enrolled_student->enrollment->student->program->code }}</td>
                    <td class="mid">{{ $enrolled_student->enrollment->year_level }}</td>
                    <td class="mid">{{ $enrolled_student->class->code }}</td>
                    <td class="mid w50">
                        <input type="checkbox" name="enrollment_ids[]" value="{{ $enrolled_student->enrollment->id }}" data-classid="{{ $enrolled_student->class->id }}" class="checkedtransfer" />
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>
<div class="mt-3">
    <div class="row">
        <div class="col-md-2">
            <label for="" class="m-0 font-weight-bold text-primary">Total Students ({{ count($enrolled_students) ?? 0 }})</label>
        </div>
        <div class="col-md-4">
            <p class="m-0 font-italic text-info" style="font-size:14px;">
                Note: (*) Denotes student is unpaid/not validated.
            </p>            
        </div>
        <div class="col-md-6 right">       
            <button type="button" id="print_classlist" class="btn btn-sm btn-danger btn-icon-split actions mb-2">
                <span class="icon text-white-50">
                    <i class="fas fa-print"></i>
                </span>
                <span class="text">Print PDF</span>
            </button>
            <button type="button" id="download_classlist" class="btn btn-sm btn-success btn-icon-split actions mb-2">
                <span class="icon text-white-50">
                    <i class="fas fa-download"></i>
                </span>
                <span class="text">Download Excel</span>
            </button>
            <button type="button" id="transfer_student" class="btn btn-sm btn-primary btn-icon-split actions mb-2">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-up"></i>
                </span>
                <span class="text">Transfer Selected</span>
            </button> 
        </div>
    </div>
</div>

