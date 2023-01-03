@if (count($enrolled_classes) > 0)
    @php
        $totalunits = 0;
    @endphp
    @foreach ($enrolled_classes as $enrolled_class)
        <tr class="label">
            <td class="mid"><input type="checkbox" name="" class="select_enrolled_class" value="{{ $enrolled_class->class->id }}" /></td>
            <td class="">{{ $enrolled_class->class->code }}</td>
            <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->code }}</td>
            <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->name }}</td>
            <td class="mid">{{ ($enrolled_class->class->isprof === 1) ? '('.$enrolled_class->class->units.')' : $enrolled_class->class->units }}</td>
            <td class="mid">{{ $enrolled_class->class->lecunits }}</td>
            <td class="mid">{{ $enrolled_class->class->labunits }}</td>
            <td class="">{{ $enrolled_class->class->schedule->schedule }}</td>
            @php
                $faculty = '';
            @endphp
            @if ($enrolled_class->class->instructor_id)
                @php
                    $fname = explode(" ", $enrolled_class->class->instructor->first_name);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $callname = ($enrolled_class->class->instructor->first_name === '(TBA)') ? 'TBA' : $acronym.'. '.$enrolled_class->class->instructor->last_name;

                    $faculty = $callname;
                @endphp
            @endif
            {{-- <td class="w150">{{ $faculty }}</td> --}}
            <td class="">{{ $enrolled_class->class->sectioninfo->code }}</td>
            <td class=" mid">{{ $enrolled_class->addedby->idno }}</td>
        </tr>
        @php
            $totalunits += $enrolled_class->class->units;
        @endphp
    @endforeach
    <tr class="nohover">
        <td colspan="4"><h6 class="m-0 font-weight-bold text-primary">Total Subjects ({{ count($enrolled_classes) }})</h6></td>
        <td colspan="6"><h6 class="m-0 font-weight-bold text-primary">(<span id="enrolledunits">{{ $totalunits }}</span>) Total Units </h6>
        </td>
    </tr>
@else
    <tr class="">
        <td class="mid" colspan="10">No records to be displayed</td>
    </tr>
@endif
