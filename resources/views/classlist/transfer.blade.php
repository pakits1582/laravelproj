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
                        <table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
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
                                            <td class="">{{ $student->student->program->code }}</td>
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
                <div class="card shadow mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Transfer To</h6>
                    </div>
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a> --}}
         </div>
     </div>
 </div>
</div>