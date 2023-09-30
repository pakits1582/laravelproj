<table id="scrollable_table_faculty_evaluations" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class="w50">Code</th>
            <th class="w120">Section</th>
            <th class="w120">Subject Code</th>
            <th class="w300">Subject Name</th>
            <th class="w30">Units</th>
            <th class="w200">Schedule</th>
            <th class="w120">Instructor</th>
            <th class="w30">Size</th>
            <th class="w30">Evaluate</th>
            <th class="w30"></th>
        </tr>
    </thead>
    <tbody>
        @if (count($classeswithslots) > 0)
            @foreach ($classeswithslots as $class)
                @if ($class['merge'] == NULL)
                    @if ($class['dissolved'] == 1)
                        <tr class="dissolved">
                    @elseif($class['tutorial'] == 1)
                        <tr class="tutorial">
                    @else
                        <tr>
                    @endif
                        @php
                            $faculty = '';
                            if ($class['instructor_id'] != NULL)
                            {
                                $fname = explode(" ", $class['first_name']);
                                $acronym = "";
                                foreach ($fname as $w) {
                                    $acronym .= $w[0];
                                }
                                $faculty = ($class['first_name'] == '(TBA)') ? 'TBA' : $acronym.'. '.$class['last_name'];
                            }
                        @endphp
                        <td class="w50">{{ $class['class_code'] }}</td>
                        <td class="w120">{{ $class['section_code'] }}</td>
                        <td class="w120"><b>{{ ($class['ismother'] == 1) ? '* ' : '' }}</b>{{ $class['subject_code'] }}</td>
                        <td class="w300">{{ $class['subject_name'] }}</td>
                        <td class="w30 mid">{{ $class['units'] }}</td>
                        <td class="w200">{{ $class['schedule'] }}</td>
                        <td class="w120">{{ $faculty }}</td>
                        <td class="w30 mid">{{ $class['totalvalidated'] }}</td>
                        <td class="w30 mid">{{ $class['evaluation'] == 1 ? 'YES' : 'NO' }}</td>
                        <td class="w30 mid">
                            @if ($class['evaluation'] == 0)
                                <a href="#" class="btn btn-primary btn-circle btn-sm evaluation_action" id="{{ $class['class_id'] }}" data-action="open" title="Open Evaluation">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <a href="#" class="btn btn-danger btn-circle btn-sm evaluation_action" id="{{ $class['class_id'] }}" data-action="close" title="Close Evaluation">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <th class="w50">&nbsp;</th>
                <th class="w120">&nbsp;</th>
                <th class="w120">&nbsp;</th>
                <th class="w300">&nbsp;</th>
                <th class="w30">&nbsp;</th>
                <th class="w200">&nbsp;</th>
                <th class="w120">&nbsp;</th>
                <th class="w30">&nbsp;</th>
                <th class="w30">&nbsp;</th>
                <th class="w30">&nbsp;</th>
                <th class="w30">&nbsp;</th>
            </tr>
        @endif
    </tbody>
</table>

