@if (count($grade_files) > 0)
    @php
        $default_period = $configuration->current_period ?? 0;
    @endphp

    @foreach ($grade_files as $grade_file)
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <h6 class="font-weight-bold text-primary">
                        {{ $grade_file['period_code'] }} - {{ $grade_file['period_name'] }}
                        {{ ($grade_file['origin'] == 1) ? '('.$grade_file['school_name'].')' : '' }}
                    </h6>
                </div>
            </div>
            <div class="col-md-6 right">
                <div class="form-group">
                    <h6 class="font-weight-bold text-primary">{{ $grade_file['program_code'] }}</h6>
                </div>                                         
            </div> 
        </div>

        @php
            $viewable = 1;

            if($default_period == $grade_file['period_id'] && count($config_schedules) > 0) 
            {
                foreach($config_schedules as $config_schedule) 
                {
                    $now = \Carbon\Carbon::now();
                    $date_from = \Carbon\Carbon::parse($config_schedule->date_from);

                    if(($config_schedule->educational_level_id == 0 && $now->lt($date_from)) ||
                        ($student->program->level->id == $config_schedule->educational_level_id && $now->lt($date_from)))
                    {
                        $viewable = 0;
                        break;
                    }
                }
            }

            $viewable = ($grade_file['origin'] == 1) ? 1 : $viewable;
        @endphp

        {{ $viewable.' - '.$grade_file['origin'] }}
        @php
            if($grade_file['origin'] == 0)
            {
                $units_sum   = 0;
                $grade_sum   = 0;
                $total_units = 0;
        @endphp
                <div class="table-responsive" id="table_data">
                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                        <thead class="">
                            <tr>
                                <th class="w30"></th>
                                <th class="w150">Section</th>
                                <th class="w50">Class</th>
                                <th class="w150">Subject Code</th>
                                <th class="">Subject Description</th>
                                <th class="w50">Units</th>
                                <th class="w50">Grade</th>
                                <th class="w50">C. G.</th>
                                <th class="w150">Remark</th>
                                <th class="w150">Instructor</th>
                            </tr>
                        </thead>
                        <tbody class="text-black" id="">
                            @if (count($grade_file['grades']) > 0)
                                @foreach ($grade_file['grades'] as $grade)
                                    <tr>
                                        <td class="">{{ $loop->iteration }}</td>
                                        <td>{{ $grade['sectioncode'] }}</td>
                                        <td>{{ $grade['classcode'] }}</td>
                                        <td>{{ ($grade['gwa'] == 1) ? '*'.$grade['subjectcode'] : $grade['subjectcode'] }}</td>
                                        <td>{{ $grade['subjectname'] }}</td>
                                        <td class="mid">{{ $grade['units'] }}</td>
                                        <td class="mid">{{ ($grade['final'] == 1) ? $grade['grade'] : '' }}</td>
                                        <td class="mid">{{ ($grade['final'] == 1) ? $grade['completion_grade'] : '' }}</td>
                                        @php
                                            $remark = '';
                                            if($grade['final'] == 1){
                                                $remark = ($grade['completion_grade'] != "") ? $grade['completion_remark'] : $grade['remark'];
                                            }
                                        @endphp
                                        <td class="">{{ $remark }}</td>
                                        @php
                                            $faculty = '';
                                        @endphp
                                        @if ($grade['instructor_id'])
                                            @php
                                                $fname = explode(" ", $grade['first_name']);
                                                $acronym = "";
                                                foreach ($fname as $w) {
                                                    $acronym .= $w[0];
                                                }
                                                $faculty = ($grade['first_name'] === '(TBA)') ? 'TBA' : $acronym.'. '.$grade['last_name'];
                                            @endphp
                                        @endif
                                        <td class="">{{ $faculty }}</td>
                                    </tr>
                                    @php
                                        $total_units += $grade['units'];

                                        //START COMPUTATION OF AVERAGE
                                        if($grade['final'] == 1){
                                            if($grade['gwa'] == 0 && is_numeric($grade['grade']))
                                            {
                                                $units_sum += $grade['units'];
                                                $grade_sum += $grade['grade'] * $grade['units'];
                                            }else if($grade['grade'] == 'INC'){
                                                $units_sum += $grade['units'];
                                                $grade_sum += is_numeric($grade['completion_grade']) ? $grade['completion_grade'] * $grade['units'] : 0;
                                            }
                                        }
                                    @endphp
                                @endforeach
                            @endif
                        </tbody>
                        @php
                            $gwa =  ($grade_sum > 0) ? number_format(@($grade_sum/$units_sum),2) : '';
                        @endphp
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <div class="m-0 font-italic text-info"><span>Note: </span>* in subject code denotes subject is excluded in GWA.
                                </td> 
                                <td class="mid font-weight-bold">{{ $total_units }}</td>    
                                <td class="mid font-weight-bold">{{ $gwa }}</td> 
                                <td colspan="3"></td>      
                            </tr>
                        </tfoot>
                    </table>
                </div>
        @php
            }else{
        @endphp
                <div class="table-responsive" id="table_data">
                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                        <thead class="">
                            <tr>
                                <th class="w30"></th>
                                <th class="w150">School</th>
                                <th class="w150">Program</th>
                                <th class="w150">Subject Code</th>
                                <th class="">Subject Description</th>
                                <th class="w50">Units</th>
                                <th class="w50">Grade</th>
                                <th class="w50">C. G.</th>
                                <th class="w50">Equiv</th>
                                <th class="w150">Remark</th>
                            </tr>
                        </thead>
                        <tbody class="text-black" id="">
                            @if (count($grade_file['grades']) > 0)
                                @foreach ($grade_file['grades'] as $grade)
                                    <tr>
                                        <td class="mid">{{ $loop->iteration }}</td>
                                        <td>{{ $grade_file['school_code'] }}</td>
                                        <td>{{ $grade_file['program_code'] }}</td>
                                        <td>{{ $grade['subject_code'] }}</td>
                                        <td>{{ $grade['subject_description'] }}</td>
                                        <td class="mid">{{ $grade['units'] }}</td>
                                        <td class="mid">{{ $grade['grade'] }}</td>
                                        <td class="mid">{{ $grade['completion_grade'] }}</td>
                                        <td class="mid">{{ $grade['equivalent_grade'] }}</td>
                                        <td>{{ $grade['remark'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10">
                                    <div class="m-0 font-italic text-info"><span>Note: </span>Grade file is from external source.</div>
                                </td> 
                            </tr>
                        </tfoot>
                    </table>
                </div>
        @php
            }
        @endphp
    @endforeach
@else
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@endif