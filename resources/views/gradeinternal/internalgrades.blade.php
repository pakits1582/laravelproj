@if (count($internal_grades) > 0)
    @php
        $units_sum = 0;
	    $grade_sum   = 0;
	    $total_units = 0;
    @endphp
    @foreach ($internal_grades as $internal_grade)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="">{{ $internal_grade->sectioncode }}</td>
            <td class="">{{ $internal_grade->classcode }}</td>
            <td class="">{{ ($internal_grade->wga == 1) ? '*'.$internal_grade->subjectcode : $internal_grade->subjectcode }}</td>
            <td class="">{{ $internal_grade->subjectname }}</td>
            <td class="tutorial" contenteditable="true" id="{{ $internal_grade->id }}" data-value="{{ ($internal_grade->final == 1) ? $internal_grade->grade : '' }}">{{ ($internal_grade->final == 1) ? $internal_grade->grade : '' }}</td>
            <td class="">{{ ($internal_grade->final == 1) ? $internal_grade->completion_grade : '' }}</td>
            <td class="mid">{{ $internal_grade->units }}</td>
            @php
                $remark = '';
                if($internal_grade->final == 1){
                    $remark = ($internal_grade->completion_grade != "") ? $internal_grade->completion_remark : $internal_grade->remark;
                }
            @endphp
            <td class="">{{ $remark }}</td>
            @php
                $faculty = '';
            @endphp
            @if ($internal_grade->instructor_id)
                @php
                    $fname = explode(" ", $internal_grade->first_name);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $faculty = ($internal_grade->first_name === '(TBA)') ? 'TBA' : $acronym.'. '.$internal_grade->last_name;
                @endphp
            @endif
            <td class="">{{ $faculty }}</td>
            <td class="mid">
                <a href="#" id="{{ $internal_grade->id }}" class="edit_internalgrade btn btn-primary btn-circle btn-sm" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="11">No records to be displayed!</td></tr>
@endif