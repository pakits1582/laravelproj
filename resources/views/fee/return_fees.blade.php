<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="feeTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Fee Type</th>
                <th>Default Value</th>
                <th class="w150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($fees) > 0)
                @foreach ($fees as $fee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $fee->code }}</td>
                        <td>{{ $fee->name }}</td>
                        <td>{{ $fee->feetype->type }}</td>
                        <td>{{ $fee->default_value }}</td>
                        <td class="mid">
                            <a href="{{ route('fees.edit', ['fee' => $fee->id ]) }}" class="btn btn-primary btn-icon-split">
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
        $fees instanceof \Illuminate\Pagination\Paginator ||
        $fees instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $fees->onEachSide(1)->links() }}
        Showing {{ $fees->firstItem() }} to {{ $fees->lastItem() }} of total {{$fees->total()}} entries
    @endif
</div>