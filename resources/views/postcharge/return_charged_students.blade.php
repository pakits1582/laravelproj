
@if (count($chargedstudents) > 0)
    @foreach ($chargedstudents as $chargedstudent)
        <tr class="checkstd">
            <td class="w30 mid">
                <input type="checkbox" name="enrollment_ids[]" value="{{ $chargedstudent->enrollment->id }}" class="students">
            </td>
            <td class="w50">{{ $loop->iteration }}</td>
            <td class="w100">{{ $chargedstudent->enrollment->student->user->idno }}</td>
            <td>{{ $chargedstudent->enrollment->student->last_name }}, {{ $chargedstudent->enrollment->student->first_name }} {{ $chargedstudent->enrollment->student->name_suffix }} {{ $chargedstudent->enrollment->student->middle_name }}</td>
            <td class="w100">{{ $chargedstudent->enrollment->program->code }}-{{ $chargedstudent->enrollment->year_level }}</td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="5">No records to be displayed!</td></tr>
@endif
