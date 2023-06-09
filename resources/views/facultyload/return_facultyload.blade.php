@if (count($faculty_loads) > 0)
    @foreach ($faculty_loads as $load)
        <tr>
            @php
                $faculty = '';
                if ($load->instructor_id != NULL)
                {
                    $fname = explode(" ", $load->first_name);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $faculty = ($load->first_name == '(TBA)') ? 'TBA' : $acronym.'. '.$load->last_name;
                }
            @endphp
            <td class="w150">{{ $faculty }}</td>
            <td class="w50">{{ $load->code }}</td>
            <td class="w120">{{ $load->section_code }}</td>
            <td class="w120">{{ $load->subject_code }}</td>
            <td class="">{{ $load->subject_name }}</td>
            <td class="">{{ $load->schedule }}</td>
            <td class="w30 mid loadunits">{{ $load->loadunits }}</td>
            <td class="w30 mid">{{ $load->lecunits }}</td>
            <td class="w30 mid">{{ $load->labunits }}</td>
            <td class="w30 mid">{{ $load->units }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="10" class="mid">No records to be displayed!</td>
    </tr>
@endif