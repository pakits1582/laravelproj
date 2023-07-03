<div class="modal fade" id="transfer_students" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Transfer Students</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="POST" id="form_transferstudents" role="form" class="">
                @csrf
                <div class=" py-0 px-1"> 
                    <div class="card shadow mb-2">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{ $class->sectioninfo->code }} - ({{ $class->code }}) {{ $class->curriculumsubject->subjectinfo->code }} - {{ $class->curriculumsubject->subjectinfo->name }}</h6>
                        </div>
                        <div class="card-body">
                            <table id="scrollable_table_transfer" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
                                <thead>
                                    <tr>
                                        <th class="">#</th>
                                        <th class="">ID Number</th>
                                        <th class="">Name</th>
                                        <th class="">Program</th>
                                        <th class="">Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($enrollments))
                                        @foreach ($enrollments as $enrollment)
                                            <tr>
                                                <td class="">
                                                    {{ $loop->iteration }}
                                                    <input type="hidden" name="enrollment_ids[]" value="{{ $enrollment->id }}">
                                                    <input type="hidden" name="class_ids[]" value="{{ $enrollment->class_id }}">
                                                </td>
                                                <td class="">{{ $enrollment->student->user->idno }}</td>
                                                <td class="">{{ $enrollment->student->name }}</td>
                                                <td class="">{{ $enrollment->program->code }}</td>
                                                <td class="mid">{{ $enrollment->year_level }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
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
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Transfer selected students to class</h6>
                        </div>
                        <div class="card-body">
                            <p class="font-italic text-info">Note: (*) Type class code to search class to transfer to.</p>
                            <div class="row mb-1 align-items-center">
                                <div class="col-md-2">
                                    <label for="period_id" class="m-0 font-weight-bold text-primary">Class Code</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="" placeholder="Type to search" class="form-control text-uppercase" id="class_code_keyword">
                                </div>
                                <div class="col-md-2">
                                    <label for="period_id" class="m-0 font-weight-bold text-primary">Subject Code</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="" readonly class="form-control clearable" id="subject_code">
                                </div>
                                <div class="col-md-1">
                                    <label for="period_id" class="m-0 font-weight-bold text-primary">Units</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="" readonly class="form-control clearable" id="units">
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-md-2">
                                    <label for="period_id" class="m-0 font-weight-bold text-primary">Description</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name=""  readonly class="form-control clearable" id="description">
                                </div>
                            </div>
                            <div class="row mb-1 align-items-center">
                                <div class="col-md-2">
                                    <label for="period_id" class="m-0 font-weight-bold text-primary">Schedule</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="" readonly class="form-control clearable" id="schedule">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-2">
                                    <label for="period_id" class="m-0 font-weight-bold text-primary">Instructor</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="" readonly class="form-control clearable" id="instructor">
                                </div>
                            </div>
                            <input type="hidden" name="transferto_class_id" id="transferto_class_id" class="clearable" value="" />
                            <input type="hidden" name="transferfrom_class_id" id="transferfrom_class_id" class="clearable" value="{{ $class->id }}" />
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Transfer Selected Students</button>
                        </div>
                    </div>
                </div>
            <form>
         </div>
     </div>
 </div>
</div>