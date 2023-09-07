<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped table-hover" id="" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th class="w50">#</th>
                <th>Application No.</th>
                <th class="">Name</th>
                <th class="">Program</th>
                <th class="">Classification</th>
                <th class="">Application Date</th>
                <th class=""></th>
            </tr>
        </thead>
        <tbody>
            @if ($online_admissions !== null && count($online_admissions) > 0)
                @foreach ($online_admissions as $applicant)
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
                            <a href="{{ route('admissions.viewapplication', ['applicant' => $applicant->id ]) }}" id="{{ $applicant->id }}" class="btn btn-primary btn-circle btn-sm view_admission_application" title="View Admission Application">
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

