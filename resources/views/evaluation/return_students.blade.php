<div class="table-responsive" id="table_data">
    <table class="table table-bordered" id="instructorTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Program</th>
                <th>Year Level</th>
                <th>Curriculum</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($students) > 0)
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->user->idno }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->program->code }}</td>
                        <td>{{ Helpers::yearLevel($student->year_level) }}</td>
                        <td>{{ $student->curriculum->curriculum  }}</td>
                        <td class="mid">
                            {{-- <a href="#" class="btn btn-primary btn-icon-split"> --}}
                            <a href="{{ route('evaluations.show', ['evaluation' => $student->id ]) }}" class="btn btn-sm btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text">Evaluate</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    {{ $students->onEachSide(1)->links() }}
    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of total {{$students->total()}} entries
</div>