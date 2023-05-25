{{-- @dump($grade_files) --}}
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Student's Grade File</h6>
            </div>
            <div class="card-body" id="return_studentledger">
                @if (count($grade_files) > 0)
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
                            if($grade_file['origin'] == 0)
                            {
                        @endphp
                                <div class="table-responsive" id="table_data">
                                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                        <thead class="">
                                            <tr>
                                                <th class="w30"></th>
                                                <th class="w150">Section</th>
                                                <th class="w50">Class</th>
                                                <th class="">Subject Code</th>
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
                                                        <td class="mid">{{ $loop->iteration }}</td>
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
                                                @endforeach
                                            @endif
                                        </tbody>
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
                                                <th class="">Subject Code</th>
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
                                    </table>
                                </div>
                        @php
                            }
                        @endphp
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

