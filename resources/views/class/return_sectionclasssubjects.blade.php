@if(count($section_subjects) > 0)
    @foreach ($section_subjects as $section_subject)
        @php
            $row_color = '';
            if($section_subject->dissolved === 1)
            {
                $row_color = 'dissolved';
            }elseif ($section_subject->tutorial === 1) {
                $row_color = 'tutorial';
            }elseif ($section_subject->f2f === 1) {
                $row_color = 'f2f';
            }
        @endphp
        <tr class="label {{ $row_color }}" id="check_{{ $section_subject->id }}">
            <td><input type="checkbox" data-classid="{{ $section_subject->id }}" class="checks" id="check_{{ $section_subject->id }}" /></td>
            <td class="mid font-weight-bold">{{ $section_subject->code }}</td>
            <td>{{ $section_subject->curriculumsubject->subjectinfo->code }}</td>
            <td>
                {{-- @if ($section_subject->ismother > 0)
                    <a href="#" class="viewmerge" id="{{ $section_subject->id }}"  title="View Merge Subjects"><strong>*</strong>
                @else --}}
                @if ($section_subject->merge > 0)
                    <a href="#" class="unmerge" id="{{ $section_subject->id }}"  title="Unmerge Subjects" data-mergeto="({{ $section_subject->mergetomotherclass->code }}) {{ $section_subject->mergetomotherclass->curriculumsubject->subjectinfo->code }} - {{ $section_subject->mergetomotherclass->curriculumsubject->subjectinfo->name }}"><strong>({{ $section_subject->mergetomotherclass->code }})</strong>
                @else
                    <a href="#" class="merge" id="{{ $section_subject->id }}"  title="Merge Subjects">
                        @if ($section_subject->ismother > 0)
                            <b>*</b>
                        @endif
                @endif
                <b>{{ ($section_subject->dissolved === 1) ? '[ DISSOLVED ]' : '' }}</b>
                {{ $section_subject->curriculumsubject->subjectinfo->name }}</a>
            </td>
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
            <td>{{ $section_subject->curriculumsubject->curriculum->curriculum }}</td>
        </tr>
    @endforeach
@else
    <tr><td colspan="14" class="mid">No records to be displayed</td></tr>
@endif