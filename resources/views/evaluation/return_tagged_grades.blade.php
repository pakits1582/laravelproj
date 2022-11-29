@if (count($allgrades) > 0)
    @foreach ($allgrades as $grade)
        @php
            $checked = '';
            $isselected = false;
            $istaggedcolor = '';
            $istagged = 0;

            if(Helpers::is_column_in_array($grade['id'], 'grade_id', $all_tagged_grades->toArray()) !== false)
            {
                foreach ($all_tagged_grades as $key => $tagged_grade) {
                    $origin = ($tagged_grade->origin === 0) ? 'internal' : 'external';

                    if($grade['id'] === $tagged_grade->grade_id && $grade['origin'] === $origin)
                    {
                        if($tagged_grade->curriculum_subject_id === $curriculum_subject->id){
                            $istagged = 1;
                            $checked = 'checked';
                            $isselected = true;
                        }else{
                            $istagged = 1;
                            $istaggedcolor = 'success';
                        }
                    }
                }
            }
        @endphp        
        <tr class="label bg-{{ ($isselected) ? 'warning' : $istaggedcolor }}" id="remove_{{ $grade['id'] }}_{{ $grade['origin'] }}" >
            <td class="w50">
                <input type="checkbox" name="cboxtag[]" value="{{ $grade['id'] }}" class="cboxtag nomargin" id="{{ $grade['id'] }}" data-origin="{{ $grade['origin'] }}"
                    {{ $checked }} data-istagged="{{ $istagged }}"
                />
                <input type="hidden" name="origin_{{ $grade['id'] }}" value="{{ $grade['origin'] }}"  />
                <input type="hidden" name="istagged_{{ $grade['id'] }}" value="{{ $istagged }}"  />
            </td>
            @php
                $subject_code = ($grade['origin'] === 'external') ? '*'.$grade['subject_code'] : $grade['subject_code'];
            @endphp
            <td class="w120 font-weight-bold">{{ $subject_code }}</td>
            <td>{{ $grade['subject_name'] }}</td>
            <td class="w100 mid">{{ $grade['units'] }}</td>
            <td class="w100 mid">{{ $grade['grade'] }}</td>
            <td class="w100 mid">{{ $grade['completion_grade'] }}</td>
        </tr>
    @endforeach
@else
    <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
@endif