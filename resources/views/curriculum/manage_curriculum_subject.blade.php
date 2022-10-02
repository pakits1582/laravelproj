 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Manage Curriculum Subject (Curriculum {{ $curriculum_subject->curriculum->curriculum }})</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-3"> 
                <h6 class="font-weight-bold text-800 text-success mb-4">{{ $curriculum_subject->subjectinfo->code }} - {{ $curriculum_subject->subjectinfo->name }}</h6>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add pre-requisites, corequisites, equivalent subjects</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mx-auto">
                                <div class="bg-white rounded-lg shadow-sm p-3">
                                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                                    <!-- credit card info-->
                                    <div id="nav-tab-card" class="tab-pane fade show active">
                                        @if(Session::has('message'))
                                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                        @endif
                                        <form method="POST" action="{{ route('curriculum.storemanagecurriculumsubject') }}"  role="form" id="form_manage_curriculum_subject">
                                            @csrf
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">* Save subject to</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="saveto" class="form-control" id="saveto" required>
                                                            <option value="prerequisites" selected>Pre-requisites subjects</option>
                                                            <option value="corequisites">Co-requisites subjects</option>
                                                            <option value="equivalents">Equivalent subjects</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="name" class="m-0 font-weight-bold text-primary">Search Subject</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="search_subject_currsubmgmt" class="form-control text-uppercase" value="" placeholder="Type here to search subject">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="year_level" class="m-0 font-weight-bold text-primary">Search Result</label>
                                                            <p class="font-italic text-info">Note: CTRL + click for multiple selection</p>
                                                            <select  class="form-control select currsubadding" id="search_result_currsubmgmt" multiple size="10">
                                                                @if ($curriculum_subjects)
                                                                    @foreach ($curriculum_subjects as $curr_subject)
                                                                        <option value="{{ $curr_subject->id }}" id="option_{{ $curr_subject->subjectinfo->id }}" title="{{ $curr_subject->subjectinfo->name }}">({{ $curr_subject->subjectinfo->units }}) - [ {{ $curr_subject->subjectinfo->code }} ] {{ $curr_subject->subjectinfo->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 mid  my-auto">
                                                        <div class="form-group">
                                                            <a href="#" class="btn btn-primary btn-icon-split mb-2" id="button_moveright_currsubmgmt">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-plus-square"></i>
                                                                </span>
                                                                <span class="text">==></span>
                                                            </a>
                                                            <a href="#" class="btn btn-primary btn-icon-split mb-2" id="button_moveleft_currsubmgmt">
                                                                <span class="text"><==</span>
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-plus-square"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="year_level" class="m-0 font-weight-bold text-primary">* Subjects to be added</label>
                                                            <p class="font-italic text-info">Note: CTRL + click for multiple selection</p>
                                                            <select name="subjects[]" class="form-control select currsubadding" id="selected_subjects_currsubmgmt" required multiple size="10">
                                                                <option value="">- Add at least one subject -</option>
                                                            </select>
                                                            <p class="font-italic text-info">Note: (*)  Only highlighted subjects will be added.</p>
                                                            <div id="error_subjects"></div>
                                                            @error('subjects')
                                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="curriculum_subject" value="{{ $curriculum_subject->id }}" id="curriculum_subject" />
                                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Subjects</button>
                                        </form>
                                    </div>
                                  <!-- End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Pre-requisite Subjects</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive-sm">
                                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                        <thead class="text-primary">
                                            <tr>
                                                <th class="w150">Course No.</th>
                                                <th class="mid">Descriptive Title</th>
                                                <th class="w20 mid">Units</th>
                                                <th class="w20 mid"><i class="fas fa-fw fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black" id="return_prerequisites">
                                            @if(count($curriculum_subject->prerequisites) > 0)
                                                @foreach ($curriculum_subject->prerequisites as $prerequisite)
                                                    <tr>
                                                        <td>{{ $prerequisite->curriculumsubject->subjectinfo->code }}</td>
                                                        <td>{{ $prerequisite->curriculumsubject->subjectinfo->name }}</td>
                                                        <td class="mid">{{ $prerequisite->curriculumsubject->subjectinfo->units }}</td>
                                                        <td class="mid">
                                                            <a href="#" class="btn btn-danger btn-circle btn-sm delete_item" id="{{ $prerequisite->id }}" data-action="prerequisites" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="4" class="mid">No records to be displayed</td></tr>
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CO-REQUISITES-->
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Co-requisite Subjects</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive-sm">
                                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                        <thead class="text-primary">
                                            <tr>
                                                <th class="w150">Course No.</th>
                                                <th class="mid">Descriptive Title</th>
                                                <th class="w20">Units</th>
                                                <th class="w20 mid"><i class="fas fa-fw fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black" id="return_corequisites">
                                            @if(count($curriculum_subject->corequisites) > 0)
                                                @foreach ($curriculum_subject->corequisites as $corequisite)
                                                    <tr>
                                                        <td>{{ $corequisite->curriculumsubject->subjectinfo->code }}</td>
                                                        <td>{{ $corequisite->curriculumsubject->subjectinfo->name }}</td>
                                                        <td class="mid">{{ $corequisite->curriculumsubject->subjectinfo->units }}</td>
                                                        <td class="mid">
                                                            <a href="#" class="btn btn-danger btn-circle btn-sm delete_item" id="{{ $corequisite->id }}" data-action="corequisites" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="4" class="mid">No records to be displayed</td></tr>
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- EQUIVALENT SUBJECTS -->
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Equivalent Subjects</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive-sm">
                                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                        <thead class="text-primary">
                                            <tr>
                                                <th class="w150">Course No.</th>
                                                <th class="mid">Descriptive Title</th>
                                                <th class="w20">Units</th>
                                                <th class="w20 mid"><i class="fas fa-fw fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black" id="return_equivalents">
                                            @if(count($curriculum_subject->equivalents) > 0)
                                                @foreach ($curriculum_subject->equivalents as $equivalent)
                                                    <tr>
                                                        <td>{{ $equivalent->subjectinfo->code }}</td>
                                                        <td>{{ $equivalent->subjectinfo->name }}</td>
                                                        <td class="mid">{{ $equivalent->subjectinfo->units }}</td>
                                                        <td class="mid">
                                                            <a href="#" class="btn btn-danger btn-circle btn-sm delete_item" id="{{ $equivalent->id }}" data-action="equivalents" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="4" class="mid">No records to be displayed</td></tr>
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
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