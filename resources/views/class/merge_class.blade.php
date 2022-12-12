 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Merge Class Subjects</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-3"> 
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $class['sectioninfo']['code'] }} - ({{ $class['code'] }}) {{ $class['curriculumsubject']['subjectinfo']['code'] }} - {{ $class['curriculumsubject']['subjectinfo']['name'] }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3 mid">
                                <label for="search_classtomerge" class="m-0 font-weight-bold text-primary">Search Class to Merge</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control text-uppercase" id="search_classtomerge" value="">
                            </div>
                        </div>
                        <p class="font-italic text-info">Note: You can search multiple subject code by separating it by comma (,) code 1, code 2..</p>
                        <form action="" method="post" id="form_merge_class">
                            <div class="col-xs-8 col-xs-offset-2 well">
                                <table class="table table-scroll table-striped table-bordered" style="">
                                    <thead>
                                        <tr>
                                            <th class="w20">#</th>
                                            <th class="w80">Code</th>
                                            <th class="w150">Section</th>
                                            <th class="w150">Subject</th>
                                            <th class="">Schedule</th>
                                            <th class="w150">Instructor</th>
                                            <th class="w100">Enrolled</th>
                                            <th class="w100">Slots</th>
                                        </tr>
                                    </thead>
                                    <tbody id="return_search_classtomerge">
                                        @for ($x=1;$x<=7;$x++)
                                            <tr class="">
                                                <td class="w20">&nbsp;</td>
                                                <td class="w80">&nbsp;</td>
                                                <td class="w150">&nbsp;</td>
                                                <td class="w150">&nbsp;</td>
                                                <td class="">&nbsp;</td>
                                                <td class="w150">&nbsp;</td>
                                                <td class="w100">&nbsp;</td>
                                                <td class="w100">&nbsp;</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="class_id" id="class_id" value="{{ $class['id'] }}"  />
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Merge Classes</button>
                        </form>
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