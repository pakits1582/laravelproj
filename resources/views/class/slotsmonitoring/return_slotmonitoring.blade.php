@if (count($classeswithslots) > 0)
    @foreach ($classeswithslots as $class)
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
            <td class="w120"><b>{{ ($class['mothercode'] != '') ? '('.$class['mothercode'].') ' : '' }}</b>{{ $class['subject_code'] }}</td>
            <td class="w300">{{ $class['subject_name'] }}</td>
            <td class="w30 mid">{{ $class['units'] }}</td>
            <td class="w200">{{ $class['schedule'] }}</td>
            <td class="w120">{{ $faculty }}</td>
            <td class="w30 mid">{{ $class['slots'] }}</td>
            <td class="w30 mid">{{ $class['totalenrolled'] }}</td>
            <td class="w30 mid">{{ $class['totalvalidated'] }}</td>
            <td class="w30 mid">{{ $class['remainingslot'] }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <th class="w50">&nbsp;</th>
        <th class="w150">&nbsp;</th>
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