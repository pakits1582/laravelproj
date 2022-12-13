@if (count($classes) > 0)
    @foreach ($classes as $class)
        <tr class="">
            <td class="w50 mid"><input type="checkbox" name="class_ids" class="" id="" value="{{ $class->id }}" /></td>
            <td class="w80">{{ $class->code }}</td>
            <td class="w150">{{ $class->sectioninfo->code }}</td>
            <td class="w150">{{ $class->curriculumsubject->subjectinfo->code }}</td>
            <td class="">{{ $class->schedule->schedule }}</td>
            @php
                $faculty = '';
            @endphp
            @if ($class->instructor_id)
                @php
                    $fname = explode(" ", $class->instructor->first_name);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $callname = ($class->instructor->first_name === '(TBA)') ? 'TBA' : $acronym.'. '.$class->instructor->last_name;

                    $faculty = $callname;
                @endphp
            @endif
            <td class="w150">{{ $faculty }}</td>
            <td class="w100 mid">{{ $class->enrolledstudents->count() }}</td>
            <td class="w100 mid">{{ $class->slots }}</td>
        </tr>
    @endforeach
@else
    <tr class="">
        <td class="mid" colspan="8">No records to be displayed</td>
    </tr>
@endif
