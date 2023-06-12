<table id="scrollable_table" class="table table-striped table-bordered hover" style="width:100%;">
    <thead>
        <tr>
            <th class="w150">Name</th>
            <th class="w50">Code</th>
            <th class="w120">Section</th>
            <th class="w120">Subject</th>
            <th class="">Description</th>
            <th class="">Schedule</th>
            <th class="w30">Load</th>
            <th class="w30">Lec</th>
            <th class="w30">Lab</th>
            <th class="w30">Units</th>
        </tr>
    </thead>
    <tbody>
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
                <td class="w150">&nbsp;</td>
                <td class="w50">&nbsp;</td>
                <td class="w120">&nbsp;</td>
                <td class="w120">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="w30">&nbsp;</td>
                <td class="w30">&nbsp;</td>
                <td class="w30">&nbsp;</td>
                <td class="w30">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>

@if (isset($other_assignments) && count($other_assignments) > 0)
    <div class="card shadow mt-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List of Faculty Other Assignments</h6>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered mb-0" style="font-size: 14px;">
                <thead class="">
                    <tr>
                        <th class="w40">#</th>
                        <th class="">Assignment</th>
                        <th class="w100">Units</th>
                    </tr>
                </thead>
                <tbody class="text-black" id="return_otherassignments">
                    @foreach ($other_assignments as $other_assignment)
                        <tr>
                            <td class="w40">{{ $loop->iteration }}</td>
                            <td class="">{{ $other_assignment->assignment }}</td>
                            <td class="w100 mid loadunits">{{ $other_assignment->units }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>    
@endif

