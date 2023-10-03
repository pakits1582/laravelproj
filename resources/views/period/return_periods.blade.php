<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="periodTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Term</th>
                <th>Year</th>
                <th>Enroll Start</th>
                <th>Add Drop Start</th>
                <th>Priority</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
             @if (count($periods) > 0)
                    @foreach ($periods as $period)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $period->code }}</td>
                            <td>{{ $period->name }}</td>
                            <td>{{ $period->terminfo->term }}</td>
                            <td>{{ $period->year }}</td>
                            <td>{{ $period->enroll_start  }}</td>
                            <td>{{ $period->adddrop_start  }}</td>
                            <td>{{ $period->priority_lvl }}</td>
                            <td class="mid">
                                <a href="{{ route('periods.edit', ['period' => $period->id ]) }}" class="btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
            @else
                <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    @if(
        $periods instanceof \Illuminate\Pagination\Paginator ||
        $periods instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $periods->onEachSide(1)->links() }}
        Showing {{ $periods->firstItem() }} to {{ $periods->lastItem() }} of total {{$periods->total()}} entries
    @endif
</div>