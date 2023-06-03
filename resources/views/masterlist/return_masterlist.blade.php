@if (count($masterlist) > 0)
    @foreach ($masterlist as $student)
        <tr>
            <td class="w70">{{ $loop->iteration }}</td>
            <td class="mid w150">{{ $student->student->user->idno }}</td>
            <td class="w500">{{ $student->student->name }}</td>
            <td class="w150">{{ $student->student->program->code }}</td>
            <td class="mid w100">{{ $student->year_level }}</td>
            <td class="mid w100">{{ $student->enrolled_units }}</td>
        </tr>
    @endforeach
@else
    <tr><td colspan="6" class="mid">No records to be displayed!</td></tr>
@endif