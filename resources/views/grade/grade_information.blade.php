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
                    <form method="POST" action="" id="form_grade_information">
                        @csrf
                        <p class="font-italic text-info">Note: All remarks will be displayed on selected display drop down of grade records of selected period or term.</p>
                        <div id="grade_remarks">
                            <div class="row mb-1 ">
                                <div class="col-md-2">
                                    <label for="" class="m-0 font-weight-bold text-primary">Display</label>
                                    <select name="displays[]" class="form-control">
                                        <option value="1">After</option>
                                        <option value="2">Before</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="remark" class="m-0 font-weight-bold text-primary">Remark</label>
                                    <textarea name="remarks[]" class="form-control text-uppercase remark" rows="2"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <label for="" class="m-0 font-weight-bold text-primary">Underline</label>
                                    <select name="underlines[]" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="year_level" class="m-0 font-weight-bold text-primary">Action</label>
                                    <button type="button" id="add_remark" class="btn btn-primary btn-icon-split mb-2">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="text">Add Remark</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h6 class="mb-3 font-weight-bold text-primary">Graduation and Thesis Information</h6>
                        <p class="font-italic text-info">Note: All entry in this area will be displayed after grade records of selected period or term.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_id" class="m-0 font-weight-bold text-primary">School</label>
                                    <select name="school_id" class="form-control" id="school_id">
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
                                    <label for="thesis_title" class="m-0 font-weight-bold text-primary">Thesis Title</label>
                                    <textarea name="thesis_title" id="thesis_title" class="form-control" rows="2"></textarea>
                                    <div id="error_thesis_title"></div> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="graduation_date" class="m-0 font-weight-bold text-primary">Graduation Date</label>
                                    <input type="text" name="graduation_date" id="graduation_date" class="form-control text-uppercase datepicker" value="" placeholder="">
                                    <div id="error_graduation_date"></div> 
                                </div>                                         
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="graduation_award" class="m-0 font-weight-bold text-primary">Graduation Award</label>
                                    <select name="graduation_award" class="form-control" id="graduation_award">
                                        <option value="">- select award -</option>
                                        <option value="1">MAGNA CUM LAUDE</option>
                                        <option value="2">SUMMA CUM LAUDE</option>
                                        <option value="3">CUM LAUDE</option>
                                    </select> 
                                    <div id="error_graduation_award"></div>  
                                </div>                                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="soresolution_id" class="m-0 font-weight-bold text-primary">S.O./Resolution</label>
                                    <select name="soresolution_id" class="form-control" id="soresolution_id">
                                        <option value="">- select award -</option>
                                        <option value="1">MAGNA CUM LAUDE</option>
                                        <option value="2">SUMMA CUM LAUDE</option>
                                        <option value="3">CUM LAUDE</option>
                                    </select> 
                                    <div id="error_soresolution_id"></div>  
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="soresolution_no" class="m-0 font-weight-bold text-primary">Number (No.)</label>
                                    <input type="text" name="soresolution_no" id="soresolution_no" class="form-control text-uppercase clearable" value="" placeholder="">
                                    <div id="error_soresolution_no"></div> 
                                </div>                                         
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="soresolution_series" class="m-0 font-weight-bold text-primary">Series</label>
                                    <input type="text" name="soresolution_series" id="soresolution_series" class="form-control text-uppercase clearable" value="" placeholder="">
                                    <div id="error_soresolution_series"></div>  
                                </div>                                         
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="issueing_office_id" class="m-0 font-weight-bold text-primary">Issued By</label>
                                    <select name="issueing_office_id" class="form-control" id="issueing_office_id">
                                        <option value="">- select award -</option>
                                        <option value="1">MAGNA CUM LAUDE</option>
                                        <option value="2">SUMMA CUM LAUDE</option>
                                        <option value="3">CUM LAUDE</option>
                                    </select> 
                                    <div id="error_issueing_office_id"></div>  
                                </div>                                         
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="issued_date" class="m-0 font-weight-bold text-primary">Issued Date</label>
                                    <input type="text" name="issued_date" id="issued_date" class="form-control text-uppercase" value="" placeholder="">
                                    <div id="error_issued_date"></div>  
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="remark" class="m-0 font-weight-bold text-primary">Remark</label>
                                    <input type="text" name="remark" id="remark" class="form-control text-uppercase" value="" placeholder="">
                                    <div id="error_remark"></div>  
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