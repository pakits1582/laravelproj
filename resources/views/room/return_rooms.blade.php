<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="roomTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Capacity</th>
                <th class="w150">Check Conflict</th>
                <th class="w150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($rooms) > 0)
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $room->code }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ ($room->excludechecking === 1) ? 'NO' : 'YES' }}</td>
                        <td class="mid">
                            <a href="{{ route('rooms.edit', ['room' => $room->id ]) }}" class="btn btn-sm btn-primary btn-icon-split">
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
        $rooms instanceof \Illuminate\Pagination\Paginator ||
        $rooms instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $rooms->onEachSide(1)->links() }}
        Showing {{ $rooms->firstItem() }} to {{ $rooms->lastItem() }} of total {{$rooms->total()}} entries
    @endif
</div>