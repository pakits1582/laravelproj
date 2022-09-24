<div class="table-responsive" id="table_data">
    <table class="table table-bordered" id="instructorTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Program</th>
                <th>Year Level</th>
                <th>Curriculum</th>
                <th>Acad Status</th>
                <th>Account</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($students) > 0)
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->user->idno }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->program->code }}</td>
                        <td>{{ Helpers::yearLevel($student->year_level) }}</td>
                        <td>{{ $student->curriculum->curriculum  }}</td>
                        <td>{{ Helpers::academicStatus($student->academic_status) }}</td>
                        <td>{{ ($student->user->is_active == 1) ? 'Active' : 'Inactive'  }}</td>
                        <td class="mid">
                            <a href="{{ route('students.edit', ['student' => $student->id ]) }}" class="btn btn-primary btn-circle btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if ($student->user->is_active == 1)
                                <a href="#" class="btn btn-danger btn-circle btn-sm user_action" id="{{ $student->user_id }}" data-action="deactivate" title="Deactivate">
                                    <i class="fas fa-trash"></i>
                                </a>
                            @else
                                <a href="#" class="btn btn-success btn-circle btn-sm user_action" id="{{ $student->user_id }}" data-action="activate" title="Activate">
                                    <i class="fas fa-power-off"></i>
                                </a>  
                            @endif
                            <a href="#" class="btn btn-info btn-circle btn-sm user_action" id="{{ $student->user_id }}" data-action="reset" title="Reset Password">
                                <i class="fas fa-undo"></i>
                            </a>
                           
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    {{ $students->onEachSide(1)->links() }}
    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of total {{$students->total()}} entries
</div>