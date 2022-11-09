@if(count($external_subjects) > 0)
    @foreach ($external_subjects as $external_subject)
        <tr class="label" id="check_{{ $external_subject->id }}">
            <td><input type="checkbox" data-id="{{ $external_subject->id }}" class="checks" id="check_{{ $external_subject->id }}" /></td>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $external_subject->gradeinfo->school->code }}</td>
            <td>{{ $external_subject->gradeinfo->program->code }}</td>
            <td>{{ $external_subject->subject_code }}</td>
            <td>{{ $external_subject->subject_description }}</td>
            <td class="mid">{{ $external_subject->grade }}</td>
            <td class="mid">{{ $external_subject->completion_grade }}</td>
            <td class="mid">{{ $external_subject->equivalent_grade }}</td>
            <td class="mid">{{ $external_subject->units }}</td>
            <td class="mid">{{ $external_subject->remark->remark }}</td>
        </tr>
    @endforeach
@else
    <tr><td colspan="13" class="mid">No records to be displayed</td></tr>
@endif