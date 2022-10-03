<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="schoolTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Address</th>
                <th class="w150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($schools) > 0)
                @foreach ($schools as $school)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $school->code }}</td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->address }}</td>
                        <td class="mid">
                            <a href="{{ route('schools.edit', ['school' => $school->id ]) }}" class="btn btn-primary btn-icon-split" title="Edit School">
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
        $schools instanceof \Illuminate\Pagination\Paginator ||
        $schools instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $schools->onEachSide(1)->links() }}
        Showing {{ $schools->firstItem() }} to {{ $schools->lastItem() }} of total {{$schools->total()}} entries
    @endif
</div>