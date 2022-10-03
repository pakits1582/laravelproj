<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="departmentTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Head</th>
                <th class="w150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($departments) > 0)
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department->code }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->headName }}</td>
                        <td class="mid">
                            <a href="{{ route('departments.edit', ['department' => $department->id ]) }}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text">Edit</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="6" class="mid">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    @if(
        $departments instanceof \Illuminate\Pagination\Paginator ||
        $departments instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $departments->onEachSide(1)->links() }}
        Showing {{ $departments->firstItem() }} to {{ $departments->lastItem() }} of total {{$departments->total()}} entries
    @endif
</div>