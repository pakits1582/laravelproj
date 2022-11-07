<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">External Grade Form</h6>
            </div>
            <div class="card-body">
                {{-- {{ dd($schools) }} --}}
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <form method="POST" action="{{ route('gradeexternals.store') }}"  role="form" id="form_externalgrade">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Grade No.</label>
                                <select name="grade_id" class="form-control" id="grade_id">
                                    <option value="">- select grade -</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <p class="m-0 font-italic text-info" style="font-size: 12px;">
                                Note: 
                                <ul class="m-0 font-italic text-info" style="font-size: 12px;">
                                    <li>If student has multiple external grade record from different schools in the same term you can view grades by selecting grade no.</li>
                                    <li>Selecting grade number will automatically display the school and program where the grade will be save.</li>
                                    <li>If grade no. is selected, changing school or program will be applied to all subjects of the grade no. after saving.</li>
                                </ul>
                            </p>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">* School</label>
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
                                <label for="term" class="m-0 font-weight-bold text-primary">* Program</label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">* Subject Code</label>
                                <input type="text" name="subject_code" id="subject_code" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                            <div id="error_subject_code"></div>                                           
                        </div>
                        <div class="col-md-6">
                            <label for="term" class="m-0 font-weight-bold text-primary">Subject Description</label>
                            <input type="text" name="subject_description" id="subject_description" class="form-control text-uppercase clearable" value="" placeholder="">                                  
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">Grade</label>
                                <input type="text" name="grade" id="grade" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">C. G.</label>
                                <input type="text" name="completion_grade" id="completion_grade" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">Units</label>
                                <input type="text" name="units" id="units" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">Remark</label>
                                <select name="remark_id" class="form-control select clearable" id="remark">
                                    <option value="">- select remark -</option>
                                    @if ($remarks)
                                        @foreach ($remarks as $remark)
                                            <option 
                                                value="{{ $remark->id }}"
                                                {{ (old('remark_id') == $remark->id) ? 'selected' : '' }}
                                            >{{ $remark->remark }}</option>
                                        @endforeach
                                    @endif
                                    <option value="addnewremark" data-toggle="modal" data-target="#modal">- Click to add new remark -</option>  
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">Equivalent</label>
                                <input type="text" name="equivalent_grade" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group mid" id="button_group">
                                <button type="submit" id="save_class" class="btn btn-success btn-icon-split mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span class="text">Save Changes</span>
                                </button>
                                <button type="button" id="edit" class="btn btn-primary btn-icon-split actions mb-2 mb-md-0" disabled>
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </button>
                                <button type="button" id="delete" class="btn btn-danger btn-icon-split actions mb-2 mb-md-0" disabled>
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                                <button type="button" id="cancel" class="btn btn-danger btn-icon-split mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancel</span>
                                </button>
                            </div>                                    
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>