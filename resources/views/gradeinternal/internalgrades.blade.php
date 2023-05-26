@if (count($internal_grades) > 0)
    @php
        $units_sum = 0;
	    $grade_sum   = 0;
	    $total_units = 0;
    @endphp
    @foreach ($internal_grades as $internal_grade)
        <tr  id="remove_{{ $internal_grade->id }}">
            <td>{{ $loop->iteration }}</td>
            <td class="">{{ $internal_grade->sectioncode }}</td>
            <td class="">{{ $internal_grade->classcode }}</td>
            <td class="">{{ ($internal_grade->gwa == 1) ? '*'.$internal_grade->subjectcode : $internal_grade->subjectcode }}</td>
            <td class="">{{ $internal_grade->subjectname }}</td>
            <td class="mid">{{ $internal_grade->units }}</td>
            <td class="editable tutorial mid text-uppercase" contenteditable="true" id="{{ $internal_grade->id }}" data-value="{{ ($internal_grade->final == 1) ? $internal_grade->grade : '' }}">{{ ($internal_grade->final == 1) ? $internal_grade->grade : '' }}</td>
            <td class="">{{ ($internal_grade->final == 1) ? $internal_grade->completion_grade : '' }}</td>
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
        @php
            $total_units += $internal_grade->units;

            //START COMPUTATION OF AVERAGE
            if($internal_grade->final == 1){
                if($internal_grade->gwa == 0 && is_numeric($internal_grade->grade))
                {
                    $units_sum += $internal_grade->units;
                    $grade_sum += $internal_grade->grade * $internal_grade->units;
                }else if($internal_grade->grade == 'INC'){
                    $units_sum += $internal_grade->units;
                    $grade_sum += is_numeric($internal_grade->completion_grade) ? $internal_grade->completion_grade * $internal_grade->units : 0;
                }
            }
        @endphp
    @endforeach
    @php
        $gwa =  ($grade_sum > 0) ? number_format(@($grade_sum/$units_sum),2) : '';
    @endphp
    <tr>
        <td colspan="5">
            <div class="m-0 font-italic text-info"><span>Note: </span>* in subject code denotes subject is excluded in GWA.
        </td> 
        <td class="mid font-weight-bold">{{ $total_units }}</td>    
        <td class="mid font-weight-bold">{{ $gwa }}</td> 
        <td class="mid font-weight-bold"></td>    
        
        <td colspan="3"></td>      
    </tr>
@else
    <tr><td class="mid" colspan="11">No records to be displayed!</td></tr>
@endif