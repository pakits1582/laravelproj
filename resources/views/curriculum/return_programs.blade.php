<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped table-hover" id="programTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Years</th>
                <th>Level</th>
                <th>Head</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($programs)
                @unless (count($programs) == 0)
                    @foreach ($programs as $program)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $program->code }}</td>
                            <td>{{ $program->name }}</td>
                            <td>{{ $program->years }}</td>
                            <td>{{ $program->level->level }}</td>
                            <td>{{ $program->headName }}</td>
                            <td class="mid">
                                <div class="btn-group">
                                    @if (Helpers::getAccessAbility(Auth::user()->access->toArray(), 'curriculum', 'write_only'))
                                        <a href="{{ route('curriculum.manage', ['program' => $program->id ]) }}" class="btn btn-primary btn-circle btn-sm  inline-block"  title="Manage CUrriculum">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if (Helpers::getAccessAbility(Auth::user()->access->toArray(), 'curriculum', 'read_only'))
                                        <a href="#" class="btn btn-success btn-circle btn-sm inline-block"  title="View Curriculum">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endunless
            @else
                <tr><td colspan="6">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    @if(
        $programs instanceof \Illuminate\Pagination\Paginator ||
        $programs instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $programs->onEachSide(1)->links() }}
        Showing {{ $programs->firstItem() }} to {{ $programs->lastItem() }} of total {{$programs->total()}} entries
    @endif
</div>