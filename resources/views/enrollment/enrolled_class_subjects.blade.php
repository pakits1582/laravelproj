@php
    $total_units = 0;
    $total_subjects = 0;
@endphp
@if (count($enrolled_classes) > 0)

    @if (isset($with_checkbox) && $with_checkbox == true)
        @php
            $colspan1 = 4;
            $colspan2 = 9;
        @endphp
    @else
    @php
        $colspan1 = 3;
        $colspan2 = 10;
    @endphp
    @endif

    @foreach ($enrolled_classes as $enrolled_class)
        <tr class="label">
            @if (isset($with_checkbox) && $with_checkbox == true)
                <td class="mid">
                    <input type="checkbox" name="" class="select_enrolled_class" value="{{ $enrolled_class->class->id }}" />
                </td>
            @endif
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
            $total_units += $enrolled_class->class->units;
            $total_subjects++;
        @endphp
    @endforeach
    <tr class="nohover">
        <td colspan="{{ $colspan1 }}"><h6 class="m-0 font-weight-bold text-primary">Total Subjects ({{ count($enrolled_classes) }})</h6></td>
        <td colspan="6"><h6 class="m-0 font-weight-bold text-primary">(<span id="enrolled_units">{{ $total_units }}</span>) Total Units </h6>
        </td>
    </tr>
@else
    <tr class="">
        <td class="mid" colspan="{{ $colspan2 }}">No records to be displayed</td>
    </tr>
    <tr class="nohover">
        <td colspan="{{ $colspan1 }}"><h6 class="m-0 font-weight-bold text-primary">Total Subjects ({{ $total_subjects }})</h6></td>
        <td colspan="6"><h6 class="m-0 font-weight-bold text-primary">(<span id="enrolled_units">{{ $total_units }}</span>) Total Units </h6>
        </td>
    </tr>
@endif
