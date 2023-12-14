@if (isset($section_subjects) && count($section_subjects) > 0)
    @php
        $total_subjects = 0;
        $total_units = 0;
    @endphp
    @foreach ($section_subjects as $section_subject)
        @php
            $errors = '';
            $errors .= ($section_subject->duplicate === 1) ? '[DUPLICATE]' : ''; 
            $errors .= ($section_subject->conflict === 1) ? '[CONFLICT]' : ''; 
            $errors .= ($section_subject->total_slots_taken >= $section_subject->total_slots) ? '[FULL]' : '';
            $errors .= ($section_subject->unfinished_prerequisites) ? '[PREREQUISITE]' : '';

        @endphp
        @if ($errors != '')
            <tr class="label dissolved" id="check_{{ $section_subject->id }}">
                <td class="w30 mid"></td>
        @else
            <tr class="label" id="{{ $section_subject->id }}">
                <td class="w30 mid">
                    <input type="checkbox" name="class_ids[]" class="checked_offered" id="checked_offered_{{ $section_subject->id }}" value="{{ $section_subject->id }}" />
                </td>
        @endif
            <td class="w50"><b>{{ $section_subject->code }}</b></td>
            <td class="w120">{{ $section_subject->curriculumsubject->subjectinfo->code }}</td>
            <td><b>{{ $errors }}</b> {{ $section_subject->curriculumsubject->subjectinfo->name }}</td>
            <td class="w50 mid units">{{ $section_subject->units }}</td>
            <td class="w50 mid">{{ $section_subject->lecunits }}</td>
            <td class="w50 mid">{{ $section_subject->labunits }}</td>
            <td class="">{{ $section_subject->schedule->schedule }}</td>
            <td class="w120">{{ $section_subject->sectioninfo->name }}</td>
        </tr>
        @php
            $total_units += $section_subject->units;
            $total_subjects++;
        @endphp
    @endforeach
    <tr class="nohover">
        <td colspan="4"><h6 class="m-0 font-weight-bold text-primary">Total Subjects ({{ $total_subjects }})</h6></td>
        <td colspan="5"><h6 class="m-0 font-weight-bold text-primary">(<span id="">{{ $total_units }}</span>) Total Units </h6>
        </td>
    </tr>
@else
    <tr>
        <td class="mid" colspan="9">No records to be displayed</td>
    </tr>
@endif