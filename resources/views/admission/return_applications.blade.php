<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped table-hover" id="applicantTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th class="w50">#</th>
                <th>Application No.</th>
                <th class="">Name</th>
                <th class="">Program</th>
                <th class="">Classification</th>
                <th class="">Date</th>
                <th class=""></th>
            </tr>
        </thead>
        <tbody>
            @if ($applicants !== null && count($applicants) > 0)
                @foreach ($applicants as $applicant)
                    <tr class="label">
                        <td class="">{{ $loop->iteration }}</td>
                        <td class="">{{ $applicant->application_no }}</td>
                        <td class="">
                            <a href="{{ route('admission.show', ['application' => $applicant->id ]) }}" id="{{ $applicant->id }}" class="font-weight-bold text-primary" title="Admit Applicant">
                                {{ $applicant->name }}
                            </a>
                        </td>
                        <td class="">{{ $applicant->program->code }}</td>
                        <td class="">{{ \App\Models\Student::STUDENT_CLASSIFICATION[$applicant->classification] }}</td>
                        <td class="mid">{{ \Carbon\Carbon::parse($applicant->entry_date)->format('F d, Y') }}</td>
                        <td class="mid">
                            <a href="{{ route('applications.show', ['application' => $applicant->id ]) }}" id="{{ $applicant->id }}" class="btn btn-primary btn-circle btn-sm view_application" target="_blank" title="View Applicant">
                                <i class="fas fa-eye"></i>
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
</div>

