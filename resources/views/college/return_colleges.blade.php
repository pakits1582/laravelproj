<div class="table-responsive" id="table_data">
    <table class="table table-sm table-bordered table-striped" id="collegeTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Dean</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($colleges) > 0)
                @foreach ($colleges as $college)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $college->code }}</td>
                        <td>{{ $college->name }}</td>
                        <td>{{ $college->deanName }}</td>
                        <td class="mid">
                            <a href="{{ route('colleges.edit', ['college' => $college->id ]) }}" class="btn btn-sm btn-primary btn-icon-split">
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
        $colleges instanceof \Illuminate\Pagination\Paginator ||
        $colleges instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $colleges->onEachSide(1)->links() }}
        Showing {{ $colleges->firstItem() }} to {{ $colleges->lastItem() }} of total {{$colleges->total()}} entries
    @endif
</div>