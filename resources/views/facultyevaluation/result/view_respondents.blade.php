<div class="modal fade" id="viewRespondentsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
           <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Evaluation Respondents</h1>
           <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
           </button>
        </div>
        <div class="modal-body">
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Student List and Subject Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-1">
                                    <label for="" class="m-0 font-weight-bold text-primary">Code</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="m-0 font-weight-bold text-black">{{ $respondents['class']->code }} ({{ $respondents['class']->sectioninfo->code }})</div>
                                </div>
                                <div class="col-md-1">        
                                    <label for="" class="m-0 font-weight-bold text-primary">Instructor</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="m-0 font-weight-bold text-black">{{ $respondents['class']->instructor->name ?? '' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <label for="" class="m-0 font-weight-bold text-primary">Subject</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="m-0 font-weight-bold text-black">
                                        {{ $respondents['class']->curriculumsubject->subjectinfo->code }} - {{ $respondents['class']->curriculumsubject->subjectinfo->name }} ({{ $respondents['class']->units }} UNITS)
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="" class="m-0 font-weight-bold text-primary">Schedule</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="m-0 font-weight-bold text-black">{{ $respondents['class']->schedule->schedule ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                        <table id="scrollable_table_respondents" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
                            <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="">ID Number</th>
                                    <th class="">Name</th>
                                    <th class="">Program</th>
                                    <th class="">Year</th>
                                    <th class="">Section</th>
                                    <th class="">Class</th>
                                    <th class="">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($respondents['respondents']) > 0)
                                    @foreach ($respondents['respondents'] as $respondent)
                                        <tr>
                                            <td class="">{{ $loop->iteration }}</td>
                                            <td class="">{{ $respondent->idno }}</td>
                                            <td class="">{{ $respondent->full_name }}</td>
                                            <td class="">{{ $respondent->program_code }}</td>
                                            <td class="mid">{{ $respondent->year_level }}</td>
                                            <td class="">{{ $respondent->section_code }}</td>
                                            <td class="">{{ $respondent->class_code }}</td>
                                            <td class="mid">
                                                @php
                                                    $class = '';
                                                @endphp
                                                @switch($respondent->status)
                                                    @case(\App\Models\FacultyEvaluation::FACULTY_EVAL_UNSTARTED)
                                                        @php
                                                           $class = 'text-danger';
                                                        @endphp
                                                        @break
                                                    @case(\App\Models\FacultyEvaluation::FACULTY_EVAL_STARTED)
                                                    @php
                                                        $class = 'text-primary';
                                                    @endphp
                                                        @break
                                                    @case(\App\Models\FacultyEvaluation::FACULTY_EVAL_FINISHED)
                                                    @php
                                                        $class = 'text-success';
                                                    @endphp
                                                        @break
                                                    @default
                                                        
                                                @endswitch
                                                <span class="h6 font-weight-bold {{ $class }}">{{ \App\Models\FacultyEvaluation::FACULTY_EVAL_STATUS[$respondent->status] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                        <td class="">&nbsp;</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
           
        </div>
    </div>
</div>
</div>