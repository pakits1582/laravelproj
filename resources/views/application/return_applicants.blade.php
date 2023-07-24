<table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class="w30 mid"><input type="checkbox" name="check_all" value="1" id="check_all" /></th>
            <th class="w50">#</th>
            <th class="">Application No.</th>
            <th class="">Name</th>
            <th class="">Program</th>
            <th class="">Classification</th>
            <th class="">Date</th>
            <th class="">Period Applied</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if (count($applicants) > 0)
            @foreach ($applicants as $applicant)
                {{-- <tr class="label">
                    <td class="mid">
                        <input type="checkbox" name="student_ids[]" value="{{ $enrollment->id }}" class="checkedapplicants" id="" />
                    </td>
                    <td class="">{{ $loop->iteration }}</td>
                    <td class="">{{ $enrollment->student->user->idno }}</td>
                    <td class="">{{ $enrollment->student->name }}</td>
                    <td class="">{{ $enrollment->program->code }}</td>
                    <td class="mid">{{ $enrollment->year_level }}</td>
                    <td class="">{{ $enrollment->section->code }}</td>
                    <td class="mid">{{ $enrollment->enrolled_classes_count }}</td>
                    <td class="mid">{{ $enrollment->enrolledby->idno }}</td>
                    <td class="mid">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y') }}</td>
                    <td class="mid">
                        <a href="#" id="{{ $enrollment->id }}" class="btn btn-success btn-circle btn-sm view_classes_unpaid" title="Edit Applicant">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr> --}}
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
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
            </tr>
        @endif
    </tbody>
</table>
