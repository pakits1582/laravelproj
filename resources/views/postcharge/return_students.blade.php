
@if (count($filteredstudents) > 0)
    @foreach ($filteredstudents as $enrollment)
        <tr class="checkstd">
            <td class="w30 mid">
                <input type="checkbox" name="enrollment_ids[]" value="{{ $enrollment->id }}" class="students">
            </td>
            <td class="w50">{{ $loop->iteration }}</td>
            <td class="w100">{{ $enrollment->student->user->idno }}</td>
            <td class="w300">{{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }} {{ $enrollment->student->name_suffix }} {{ $enrollment->student->middle_name }}</td>
            <td class="w100">{{ $enrollment->program->code }}-{{ $enrollment->year_level }}</td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="5">No records to be displayed!</td></tr>
@endif
