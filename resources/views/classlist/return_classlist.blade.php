<table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th></th>
            <th class="">Code</th>
            <th class="">Section</th>
            <th class="w150">Subject Code</th>
            <th class="">Subject Name</th>
            <th class="">Units</th>
            <th class="">Schedule</th>
            <th class="w120">Instructor</th>
        </tr>
    </thead>
    <tbody>
        @if (count($classlist) > 0)
            @foreach ($classlist->toArray() as $class)
            @php
                $row_color = '';
                if($class['dissolved'] == 1)
                {
                    $row_color = 'dissolved';
                }elseif ($class['tutorial'] == 1) {
                    $row_color = 'tutorial';
                }elseif ($class['f2f'] == 1) {
                    $row_color = 'f2f';
                }
            @endphp
            <tr class="label {{ $row_color }}" id="check_{{ $class['id'] }}">
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
                    <td class="mid">
                        <input type="checkbox" name="class_checkbox" value="{{ $class['id'] }}" class="checks viewclasslist" id="check_{{ $class['id'] }}" />
                    </td>
                    <td class="">{{ $class['code'] }}</td>
                    <td class="">{{ $class['section_code'] }}</td>
                    <td class="">{{ $class['subject_code'] }}</td>
                    <td class="">{{ $class['subject_name'] }}</td>
                    <td class="mid">{{ $class['units'] }}</td>
                    <td class="">{{ $class['schedule'] }}</td>
                    <td class="w120">{{ $faculty }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
            </tr>
        @endif
    </tbody>
</table>

