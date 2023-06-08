@if (count($faculty_loads) > 0)
    @foreach ($faculty_loads as $load)
        <tr>
            @php
                $faculty = '';
                if ($load->instructor_id != NULL)
                {
                    $fname = explode(" ", $load->instructor->first_name);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $faculty = ($load->instructor->first_name == '(TBA)') ? 'TBA' : $acronym.'. '.$load->instructor->last_name;
                }
            @endphp
            <td class="">{{ $faculty }}</td>
            <td class="w50">{{ $load->code }}</td>
            <td class="w120">{{ $load->sectioninfo->code }}</td>
            <td class="w120">{{ $load->curriculumsubject->subjectinfo->code }}</td>
            <td class="">{{ $load->curriculumsubject->subjectinfo->name }}</td>
            <td class="">{{ $load->schedule->schedule }}</td>
            <td class="w40 mid">{{ $load->loadunits }}</td>
            <td class="w40 mid">{{ $load->lecunits }}</td>
            <td class="w40 mid">{{ $load->labunits }}</td>
            <td class="w40 mid">{{ $load->units }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="10">No records to be displayed!</td>
    </tr>
@endif