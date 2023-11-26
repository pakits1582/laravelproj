<table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class="">#</th>
            <th class="">Code</th>
            <th class="">Section</th>
            <th class="">Subject</th>
            <th class="">Schedule</th>
            <th class="">Instructor</th>
            <th class="">Enrolled</th>
            <th class="">Slots</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($classes))
            @if (count($classes) > 0)
                @foreach ($classes as $class)
                    <tr class="">
                        <td class="mid"><input type="checkbox" name="class_ids[]" class="checkbox_merge" value="{{ $class->id }}" /></td>
                        <td class="">{{ $class->code }}</td>
                        <td class="">{{ $class->sectioninfo->code }}</td>
                        <td class="">{{ $class->curriculumsubject->subjectinfo->code }}</td>
                        <td class="">{{ $class->schedule->schedule }}</td>
                        {{-- @php
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
                        <td class="">{{ $faculty }}</td> --}}
                        <td class="">{{ Helpers::getFacultyShortenName($class->instructor) }}</td>
                        <td class="mid">{{ $class->enrolledstudents->count() }}</td>
                        <td class="mid">{{ $class->slots }}</td>
                    </tr>
                @endforeach
            @else
                <tr class="">
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                    <td class="">&nbsp;</td>
                </tr>
            @endif
        @else 
            <tr>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>
