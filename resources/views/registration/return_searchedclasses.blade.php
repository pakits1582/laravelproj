<table id="scrollable_table_searched_classes" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class="w30">#</th>
            <th class="w50">Code</th>
            <th class="w120">Section</th>
            <th class="w120">Subject</th>
            <th>Description</th>
            <th class="w50">Units</th>
            <th>Schedule</th>
            <th class="w50">Size</th>
            <th class="w50">Max</th>
        </tr>
    </thead>
    <tbody id="">
        @if (isset($checked_subjects) && count($checked_subjects) > 0)
            @foreach ($checked_subjects as $checked_subject)
                @php
                    $errors = '';
                    $errors .= ($checked_subject->duplicate === 1) ? '[DUPLICATE]' : ''; 
                    $errors .= ($checked_subject->conflict === 1) ? '[CONFLICT]' : ''; 
                    $errors .= ($checked_subject->total_slots_taken >= $checked_subject->total_slots) ? '[FULL]' : '';
                    $errors .= ($checked_subject->unfinished_prerequisites) ? '[PREREQUISITE]' : '';
                    $errors .= ($checked_subject->section_closed === 1) ? '[SECTION CLOSED]' : '';

                @endphp
                @if ($errors != '')
                    <tr class="label dissolved" id="table_row_{{ $checked_subject->id }}">
                        <td class="w30 mid"></td>
                @else
                    <tr class="label" id="table_row_{{ $checked_subject->id }}">
                        <td class="w30 mid">
                            <input type="checkbox" name="class_ids[]" class="check_searched_class" id="check_searched_class_{{ $checked_subject->id }}" value="{{ $checked_subject->id }}" />
                        </td>
                @endif
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
        @else
            <tr>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td>&nbsp;</td>
                <td class="">&nbsp;</td>
                <td>&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>

