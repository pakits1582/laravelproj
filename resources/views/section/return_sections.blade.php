<div class="table-responsive" id="table_data">
    <table class="table table-sm table-bordered table-striped" id="sectionTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Program</th>
                <th class="w150">Year Level</th>
                <th class="w150">Min Enrollee</th>
                <th class="w150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($sections) > 0)
                @foreach ($sections as $section)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $section->code }}</td>
                        <td>{{ $section->name }}</td>
                        <td>{{ $section->programinfo->code }}</td>
                        <td>{{ $section->year }}</td>
                        <td>{{ $section->minenrollee }}</td>
                        <td class="mid">
                            <a href="{{ route('sections.edit', ['section' => $section->id ]) }}" class="btn btn-sm btn-primary btn-icon-split">
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
        $sections instanceof \Illuminate\Pagination\Paginator ||
        $sections instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $sections->onEachSide(1)->links() }}
        Showing {{ $sections->firstItem() }} to {{ $sections->lastItem() }} of total {{$sections->total()}} entries
    @endif
</div>