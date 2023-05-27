 <!-- Logout Modal-->
 <div class="modal fade" id="grade_information_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Grade Information - {{ $gradeinfo->period->name }}</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="card shadow ">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ ($gradeinfo->origin == 0) ? 'Internal' : 'External' }} Grade Information ({{ $gradeinfo->student->user->idno }}) {{ $gradeinfo->student->name }}</h6>
                </div>
                <div class="card-body">
                    <h6 class="mb-3 font-weight-bold text-primary">Grade Information Remark</h6>
                    <form method="POST" action="" id="">
                        @csrf
                        <p class="font-italic text-info">Note: All entry in this area will be displayed after grade records of selected period or term.</p>
                        <div id="grade_remarks">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year_level" class="m-0 font-weight-bold text-primary">Display</label>
                                        <select name="display[]" class="form-control" class="display">
                                            <option value="1">After</option>
                                            <option value="2">Before</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="year_level" class="m-0 font-weight-bold text-primary">Remark</label>
                                        <textarea name="note" class="form-control text-uppercase" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="year_level" class="m-0 font-weight-bold text-primary">Underline</label>
                                        <select name="underline[]" class="form-control" class="display">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="year_level" class="m-0 font-weight-bold text-primary">Add/Remove</label>
                                        <button type="button" id="add_remark" class="btn btn-primary btn-icon-split mb-2">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                            <span class="text">Add Remark</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h6 class="mb-3 font-weight-bold text-primary">Graduation and Thesis Information</h6>
                    <form method="post" id="" action="" >
                        @csrf
                        <p class="font-italic text-info">Note: All entry in this area will be displayed after grade records of selected period or term.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year_level" class="m-0 font-weight-bold text-primary">School</label>
                                    <select name="school_id" class="form-control" id="school">
                                        <option value="">- select school -</option>
                                        @if ($schools)
                                            @foreach ($schools as $school)
                                                <option 
                                                    value="{{ $school->id }}"
                                                    {{ (old('school_id') == $school->id) ? 'selected' : '' }}
                                                >( {{ $school->code }} ) {{ $school->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div id="error_school_id"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Program</label>
                                    <select name="program_id" class="form-control" id="program_id">
                                        <option value="">- select program -</option>
                                        @if ($programs)
                                                @foreach ($programs as $program)
                                                    <option 
                                                        value="{{ $program->id }}"
                                                        {{ (old('program_id') == $program->id) ? 'selected' : '' }}
                                                    >( {{ $program->code }} ) {{ $program->name }}</option>
                                                @endforeach
                                            @endif
                                    </select> 
                                    <div id="error_program_id"></div>  
                                </div>                                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year_level" class="m-0 font-weight-bold text-primary">Thesis Title</label>
                                    <textarea name="note" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Graduation Date</label>
                                    <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                </div>                                         
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Graduation Award</label>
                                    <select name="program_id" class="form-control" id="program_id">
                                        <option value="">- select award -</option>
                                        <option value="1">MAGNA CUM LAUDE</option>
                                        <option value="2">SUMMA CUM LAUDE</option>
                                        <option value="3">CUM LAUDE</option>
                                    </select> 
                                    <div id="error_program_id"></div>  
                                </div>                                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year_level" class="m-0 font-weight-bold text-primary">S.O./Resolution</label>
                                    <select name="program_id" class="form-control" id="program_id">
                                        <option value="">- select award -</option>
                                        <option value="1">MAGNA CUM LAUDE</option>
                                        <option value="2">SUMMA CUM LAUDE</option>
                                        <option value="3">CUM LAUDE</option>
                                    </select> 
                                    <div id="error_program_id"></div>  
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Number (No.)</label>
                                    <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                </div>                                         
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Series</label>
                                    <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                </div>                                         
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Issued By</label>
                                    <select name="program_id" class="form-control" id="program_id">
                                        <option value="">- select award -</option>
                                        <option value="1">MAGNA CUM LAUDE</option>
                                        <option value="2">SUMMA CUM LAUDE</option>
                                        <option value="3">CUM LAUDE</option>
                                    </select> 
                                    <div id="error_program_id"></div>  
                                </div>                                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year_level" class="m-0 font-weight-bold text-primary">Issued Date</label>
                                    <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                    <div id="error_program_id"></div>  
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Remark</label>
                                    <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                                </div>                                         
                            </div>
                        </div>
                        <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Changes</button>
                    </form>
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