@if (count($checked_subjects) > 0)
    @foreach ($checked_subjects as $checked_subject)
        @php
            $errors = '';
            $errors .= ($checked_subject->conflict === 1) ? '[CONFLICT]' : ''; 
            $errors .= ($checked_subject->total_slots_taken >= $checked_subject->total_slots) ? '[FULL]' : '';
            $errors .= ($checked_subject->unfinished_prerequisites) ? '[PREREQUISITE]' : '';

            $haspermission = 1;
            
            if(($checked_subject->conflict === 1) && Helpers::is_column_in_array('can_conflicts', 'permission', $user_permissions->toArray())  === false){
                $haspermission = 0;
            }

            if(($checked_subject->total_slots_taken >= $checked_subject->total_slots) && Helpers::is_column_in_array('can_zeroslot', 'permission', $user_permissions->toArray()) === false){
                $haspermission = 0;
            }

            if(($checked_subject->unfinished_prerequisites) && Helpers::is_column_in_array('can_prerequisite', 'permission', $user_permissions->toArray()) === false){
                $haspermission = 0;
            }

        @endphp
        <tr class="label {{ ($errors !== '') ? 'dissolved' : '' }}" id="searched_class_{{ $checked_subject->id }}">
            <td class="w30 mid">
                <input type="checkbox" name="" data-errors="{{ $errors }}" data-haspermission="{{ $haspermission }}" class="check_searched_class" value="{{ $checked_subject->id }}" />
            </td>
            <td class="w50"><b>{{ $checked_subject->code }}</b></td>
            <td class="w120">{{ $checked_subject->sectioninfo->name }}</td>
            <td class="w120">{{ $checked_subject->curriculumsubject->subjectinfo->code }}</td>
            <td><b>{{ $errors }}</b> {{ $checked_subject->curriculumsubject->subjectinfo->name }}</td>
            <td class="w50 mid units">{{ $checked_subject->units }}</td>
            <td class="">{{ $checked_subject->schedule->schedule }}</td>
            <td class="w50 mid">{{ $checked_subject->total_slots_taken }}</td>
            <td class="w50 mid">{{ $checked_subject->total_slots }}</td>
        </tr>
    @endforeach
    <input type="hidden" id="can_overloadunits" value="{{ (Helpers::is_column_in_array('can_overloadunits', 'permission', $user_permissions->toArray()) !== false) ? 1 : 0 }}" />
@else
    <tr class="">
        <td class="mid" colspan="8">No records to be displayed</td>
    </tr>
@endif