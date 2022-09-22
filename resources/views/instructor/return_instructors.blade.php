<div class="table-responsive" id="table_data">
    <table class="table table-bordered" id="instructorTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>College</th>
                <th>Educ Level</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($instructors) > 0)
                @foreach ($instructors as $instructor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $instructor->user->idno }}</td>
                        <td>{{ $instructor->name }}</td>
                        <td>{{ $instructor->collegeinfo->code }}</td>
                        <td>{{ $instructor->educlevel->level }}</td>
                        <td>{{ $instructor->deptcode  }}</td>
                        <td>{{ Helpers::getDesignation($instructor->designation) }}</td>
                        <td>{{ ($instructor->user->is_active == 1) ? 'Active' : 'Inactive'  }}</td>
                        <td class="mid">
                            <a href="{{ route('instructors.edit', ['instructor' => $instructor->id ]) }}" class="btn btn-primary btn-circle btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if ($instructor->user->is_active == 1)
                                <a href="#" class="btn btn-danger btn-circle btn-sm instructor_action" id="{{ $instructor->user_id }}" data-action="deactivate" title="Deactivate">
                                    <i class="fas fa-trash"></i>
                                </a>
                            @else
                                <a href="#" class="btn btn-success btn-circle btn-sm instructor_action" id="{{ $instructor->user_id }}" data-action="activate" title="Activate">
                                    <i class="fas fa-power-off"></i>
                                </a>  
                            @endif
                            <a href="#" class="btn btn-info btn-circle btn-sm instructor_action" id="{{ $instructor->user_id }}" data-action="reset" title="Reset Password">
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
    {{ $instructors->onEachSide(1)->links() }}
    Showing {{ $instructors->firstItem() }} to {{ $instructors->lastItem() }} of total {{$instructors->total()}} entries
</div>