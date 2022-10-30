<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="gradingsystemTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Value</th>
                <th>Remark</th>
                <th>Level</th>
                <th class="w150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($gradingsystems) > 0)
                @foreach ($gradingsystems as $gradingsystem)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gradingsystem->code }}</td>
                        <td>{{ $gradingsystem->value }}</td>
                        <td>{{ $gradingsystem->remark->remark }}</td>
                        <td>{{ $gradingsystem->level->level }}</td>
                        <td class="mid">
                            <a href="{{ route('gradingsystems.edit', ['gradingsystem' => $gradingsystem->id ]) }}" class="btn btn-primary btn-icon-split">
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
        $gradingsystems instanceof \Illuminate\Pagination\Paginator ||
        $gradingsystems instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $gradingsystems->onEachSide(1)->links() }}
        Showing {{ $gradingsystems->firstItem() }} to {{ $gradingsystems->lastItem() }} of total {{$gradingsystems->total()}} entries
    @endif
</div>