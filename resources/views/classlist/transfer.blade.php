<div class="modal fade" id="transfer_students" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document" style="">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Transfer Students</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
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
                                @if (count($students))
                                    @foreach ($students as $student)
                                        <tr>
                                            <td class="">{{ $loop->iteration }}</td>
                                            <td class="">{{ $student->student->user->idno }}</td>
                                            <td class="">{{ $student->student->name }}</td>
                                            <td class="">{{ $student->program->code }}</td>
                                            <td class="mid">{{ $student->year_level }}</td>
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
                            <div class="col-md-3">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Class Code</label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="class_code_keyword" placeholder="Type to search..." class="form-control" id="class_code_keyword">
                            </div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-md-3">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Subject Code</label>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="subject_code" readonly class="form-control" id="subject_code">
                            </div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-md-3">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Description</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="description"  readonly class="form-control" id="description">
                            </div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-md-3">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Schedule</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="schedule" readonly class="form-control" id="schedule">
                            </div>
                        </div>
                        <div class="row mb-1 align-items-center">
                            <div class="col-md-3">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Instructor</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="instructor" readonly class="form-control" id="instructor">
                            </div>
                        </div>
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Transfer Selected Students</button>
                    </div>
                </div>
            </div>
         </div>
     </div>
 </div>
</div>