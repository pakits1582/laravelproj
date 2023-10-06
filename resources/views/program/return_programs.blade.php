<div class="table-responsive" id="table_data">
    <table class="table table-sm table-bordered table-striped table-hover" id="programTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Years</th>
                <th>Level</th>
                <th>College</th>
                <th>Head</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($programs !== null && count($programs) > 0)
                @foreach ($programs as $program)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $program->code }}</td>
                        <td>{{ $program->name }}</td>
                        <td>{{ $program->years }}</td>
                        <td>{{ $program->level->level }}</td>
                        <td>{{ $program->collegeinfo->code }}</td>
                        <td>{{ $program->headName }}</td>
                        <td>{{ ($program->active === 1) ? 'YES' : 'NO' }}</td>
                        <td class="mid">
                            <a href="{{ route('programs.edit', ['program' => $program->id ]) }}" class="btn btn-sm btn-primary btn-icon-split">
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
    {{ $programs->onEachSide(1)->links() }}
    Showing {{ $programs->firstItem() }} to {{ $programs->lastItem() }} of total {{$programs->total()}} entries
</div>
