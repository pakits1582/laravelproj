
@if (count($filteredstudents) > 0)
    @foreach ($filteredstudents as $student)
        <tr>
            <td class="w30 mid"><input type="checkbox" name="student[]" value="'" class="students"></td>
            <td class="w50">{{ $loop->iteration }}</td>
            <td class="w100">{{ $student->student->user->idno }}</td>
            <td>{{ $student->student->last_name }}, {{ $student->student->first_name }} {{ $student->student->name_suffix }} {{ $student->student->middle_name }}</td>
            <td class="w100">{{ $student->program->code }}-{{ $student->year_level }}</td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="5">No records to be displayed!</td></tr>
@endif
