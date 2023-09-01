<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped table-hover" id="applicantTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th class="w30 mid"><input type="checkbox" name="check_all" value="1" id="check_all" /></th>
            <th class="w50">#</th>
            <th class="">Name</th>
            <th class="">Program</th>
            <th class="">Classification</th>
            <th class="">Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if ($applicants !== null && count($applicants) > 0)
            @foreach ($applicants as $applicant)
                <tr class="label">
                    <td class="mid">
                        <input type="checkbox" name="student_ids[]" value="{{ $applicant->id }}" class="checked_applicants" id="" />
                    </td>
                    <td class="">{{ $loop->iteration }}</td>
                    <td class="">{{ $applicant->name }}</td>
                    <td class="">{{ $applicant->program->code }}</td>
                    @php
                        switch ($applicant->classification) {
                            case 1:
                                $classification = 'NEW STUDENT';
                                break;
                            case 2:
                                $classification = 'TRANSFEREE';
                                break;
                            case 3:
                                $classification = 'GRADUATED (NEW PROGRAM)';
                                break;
                            default:
                                $classification = '';
                        }
                    @endphp
                    <td class="">{{ $classification   }}</td>
                    <td class="mid">{{ \Carbon\Carbon::parse($applicant->entry_date)->format('F d, Y') }}</td>
                    <td class="mid">
                        <a href="{{ route('applications.show', ['application' => $applicant->id ]) }}" id="{{ $applicant->id }}" class="btn btn-primary btn-circle btn-sm view_application" title="View Applicant">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('applications.edit', ['application' => $applicant->id ]) }}" id="{{ $applicant->id }}" target="_blank" class="btn btn-success btn-circle btn-sm edit_application" title="Edit Applicant">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
            </tr>
        @endif
    </tbody>
</table>
{{ $applicants->onEachSide(1)->links() }}
Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of total {{$applicants->total()}} entries
</div>

