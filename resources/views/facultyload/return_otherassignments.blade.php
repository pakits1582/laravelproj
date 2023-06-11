@if (count($other_assignments) > 0)
    @foreach ($other_assignments as $other_assignment)
        <tr>
            <td class="w40">{{ $loop->iteration }}</td>
            <td class="">{{ $other_assignment->assignment }}</td>
            <td class="w100 mid">{{ $other_assignment->units }}</td>
            <td class="w100 mid">
                <a href="#" id="{{ $other_assignment->id }}" class="delete_other_assignment btn btn-danger btn-circle btn-sm" title="Delete">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="mid">No records to be displayed!</td>
    </tr>
@endif