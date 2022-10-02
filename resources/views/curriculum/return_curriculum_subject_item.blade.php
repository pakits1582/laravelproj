@php
    switch ($table) {
        case 'prerequisites':
            $value = 'prerequisite';
            break;
        case 'corequisites':
            $value = 'corequisite';
            break;
        case 'equivalents':
            $value = 'equivalent';
            break;
    }
@endphp
@if(count($curriculum_subject->{$table}) > 0)
    @foreach ($curriculum_subject->{$table} as ${$value})
        <tr>
            <td>{{ ($table === 'equivalents') ? ${$value}->subjectinfo->code : ${$value}->curriculumsubject->subjectinfo->code }}</td>
            <td>{{ ($table === 'equivalents') ? ${$value}->subjectinfo->name : ${$value}->curriculumsubject->subjectinfo->name }}</td>
            <td class="mid">{{ ($table === 'equivalents') ? ${$value}->subjectinfo->units : ${$value}->curriculumsubject->subjectinfo->units }}</td>
            <td class="mid">
                <a href="#" class="btn btn-danger btn-circle btn-sm delete_item" id="{{ ${$value}->id }}" data-action="{{ $table }}" title="Delete">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td colspan="4" class="mid">No records to be displayed</td></tr>
@endif