<div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Enrolled Students in Class Subjects</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $class->sectioninfo->code }} - ({{ $class->code }}) {{ $class->curriculumsubject->subjectinfo->code }} - {{ $class->curriculumsubject->subjectinfo->name }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-xs-8 col-xs-offset-2 well" style="height: 420px">
                            <table class="table table-sm table-scroll table-striped table-bordered" style="">
                                <thead>
                                    <tr>
                                        <th class="w50">#</th>
                                        <th class="w120">ID Number</th>
                                        <th class="">Name</th>
                                        <th class="w150">Program</th>
                                        <th class="w100">Year</th>
                                        <th class="w150">Section</th>
                                        <th class="w100">Class</th>
                                    </tr>
                                </thead>
                                <tbody id="return_search_classtomerge" style="height: 400px;">
                                    @if (count($enrolled_students))
                                        @foreach ($enrolled_students as $enrolled_student)
                                            <tr>
                                                <td class="w50">{{ $loop->iteration }}</td>
                                                <td class="w120">{{ $enrolled_student->enrollment->student->user->idno }}</td>
                                                <td class="">{{ $enrolled_student->enrollment->student->name }}</td>
                                                <td class="w150">{{ $enrolled_student->enrollment->student->program->code }}</td>
                                                <td class="w100 mid">{{ $enrolled_student->enrollment->year_level }}</td>
                                                <td class="w150">{{ $enrolled_student->enrollment->section->code }}</td>
                                                <td class="w100">{{ $enrolled_student->class->code }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="7" class="mid">No records to be displayed!</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
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