@if(count($section_subjects) > 0)
    @foreach ($section_subjects as $section_subject)
        <tr class="label" id="check_{{ $section_subject->id }}">
            <td><input type="checkbox" data-classid="{{ $section_subject->id }}" class="checks" id="check_{{ $section_subject->id }}" /></td>
            <td class="mid font-weight-bold">{{ $section_subject->code }}</td>
            <td>{{ $section_subject->curriculumsubject->subjectinfo->code }}</td>
            <td>{{ $section_subject->curriculumsubject->subjectinfo->name }}</td>
            <td class="mid">{{ $section_subject->units }}</td>
            <td class="mid">{{ $section_subject->tfunits }}</td>
            <td class="mid">{{ $section_subject->loadunits }}</td>
            <td class="mid">{{ $section_subject->lecunits }}</td>
            <td class="mid">{{ $section_subject->labunits }}</td>
            <td class="mid">{{ $section_subject->hours }}</td>
            @php
                $faculty = '';
            @endphp
            @if ($section_subject->instructor_id)
                @php
                    $fname = explode(" ", $section_subject->instructor->first_name);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $callname = ($section_subject->instructor->first_name === '(TBA)') ? 'TBA' : $acronym.'. '.$section_subject->instructor->last_name;

                    $faculty = $callname;
                @endphp
            @endif
            <td>{{ $faculty }}</td>
            <td>{{ $section_subject->schedule->schedule }}</td>
            <td class="mid">{{ $section_subject->slots }}</td>
        </tr>
    @endforeach
@else
    <tr><td colspan="13" class="mid">No records to be displayed</td></tr>
@endif