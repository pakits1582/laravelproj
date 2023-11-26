<table id="scrollable_table_merged_classes" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    {{-- <table class="table table-sm table-striped table-bordered" style=""> --}}
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
        @if(isset($class['merged']))
            @if (count($class['merged']) > 0)
                @foreach ($class['merged'] as $class)
                    <tr id="remove_{{ $class['id'] }}">
                        <td class="mid">
                            <a href="#" class="btn btn-danger btn-circle btn-sm unmerge_class_subject" id="{{ $class['id'] }}" title="Unmerge Subject">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                        <td class="">{{ $class['code'] }}</td>
                        <td class="">{{ $class['sectioninfo']['code'] }}</td>
                        <td class="">{{ $class['curriculumsubject']['subjectinfo']['code'] }}</td>
                        <td class="">{{ $class['schedule']['schedule'] }}</td>
                        {{-- @php
                            $faculty = '';
                        @endphp
                        @if ($class['instructor_id'])
                            @php
                                $fname = explode(" ", $class['instructor']['first_name']);
                                $acronym = "";
                                foreach ($fname as $w) {
                                    $acronym .= $w[0];
                                }
                                $callname = ($class['instructor']['first_name'] === '(TBA)') ? 'TBA' : $acronym.'. '.$class['instructor']['last_name'];

                                $faculty = $callname;
                            @endphp
                        @endif
                        <td class="">{{ $faculty }}</td> --}}
                        <td class="">{{ Helpers::getFacultyShortenName($class['instructor']) }}</td>
                        <td class="mid">{{ isset($class['enrolledstudents']) ? count($class['enrolledstudents']) ?? 0 : 0 }}</td>
                        <td class="mid">{{ $class['slots'] }}</td>
                    </tr>
                @endforeach
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

