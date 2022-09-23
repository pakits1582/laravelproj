<div class="table-responsive" id="table_data">
    <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($users) > 0)
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->idno }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ ($user->is_active == 1) ? 'Active' : 'Inactive'  }}</td>
                        <td class="mid">
                            <a href="{{ route('users.edit', ['user' => $user->id ]) }}" class="btn btn-primary btn-circle btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if ($user->is_active == 1)
                            <a href="#" class="btn btn-danger btn-circle btn-sm user_action" id="{{ $user->id }}" data-action="deactivate" title="Deactivate">
                                <i class="fas fa-trash"></i>
                            </a>
                            @else
                                <a href="#" class="btn btn-success btn-circle btn-sm user_action" id="{{ $user->id }}" data-action="activate" title="Activate">
                                    <i class="fas fa-power-off"></i>
                                </a>  
                            @endif
                            <a href="#" class="btn btn-info btn-circle btn-sm user_action" id="{{ $user->id }}" data-action="reset" title="Reset Password">
                                <i class="fas fa-undo"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6" class="mid">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    {{ $users->onEachSide(1)->links() }}
    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of total {{$users->total()}} entries
</div>