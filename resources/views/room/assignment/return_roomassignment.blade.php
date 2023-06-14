@if (count($grouped_rooms) > 0)
    @foreach ($grouped_rooms as $grouped_room)
        <h3 class="mb-2 font-weight-bold text-black">Room: {{ $grouped_room['room'] }}</h3>
        <div class="table-responsive-sm">
            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                <thead class="">
                    <tr>
                        <th class="w30">Code</th>
                        <th class="w150">Section</th>
                        <th class="w300">Schedule</th>
                        <th class="w150">Subject Code</th>
                        <th class="">Subject Description</th>
                        <th class="w50">Units</th>
                        <th class="w150">Instructor</th>
                    </tr>
                </thead>
                <tbody class="text-black" id="">
                    @if (count($grouped_room['classes']) > 0)
                        @foreach ($grouped_room['classes'] as $class)
                            <tr>
                                <td class="w30">{{ $class['class_code'] }}</td>
                                <td class="w150">{{ $class['section_code'] }}</td>
                                <td class="">{{ $class['schedule'] }}</td>
                                <td class="w150">{{ $class['subject_code'] }}</td>
                                <td class="">{{ $class['subject_name'] }}</td>
                                <td class="w50 mid">{{ $class['units'] }}</td>
                                @php
                                    $faculty = '';
                                @endphp
                                @if ($class['instructor_id'])
                                    @php
                                        $fname = explode(" ", $class['first_name']);
                                        $acronym = "";
                                        foreach ($fname as $w) {
                                            $acronym .= $w[0];
                                        }
                                        $callname = ($class['first_name'] === '(TBA)') ? 'TBA' : $acronym.'. '.$class['last_name'];

                                        $faculty = $callname;
                                    @endphp
                                @endif
                                <td class="w150">{{ $faculty }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="7">No records to be displayed!</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endforeach
@else
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@endif
