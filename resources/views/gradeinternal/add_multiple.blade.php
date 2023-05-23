 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add Multiple Internal Grade Subjects</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-3"> 
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action=""  role="form" id="form_manage_curriculum_subject">
                                    @csrf
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
                                                        {{-- @if ($curriculum_subjects)
                                                            @foreach ($curriculum_subjects as $curr_subject)
                                                                <option value="{{ $curr_subject->id }}" id="option_{{ $curr_subject->subjectinfo->id }}" title="{{ $curr_subject->subjectinfo->name }}">({{ $curr_subject->subjectinfo->units }}) - [ {{ $curr_subject->subjectinfo->code }} ] {{ $curr_subject->subjectinfo->name }}</option>
                                                            @endforeach
                                                        @endif --}}
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
                                    <input type="hidden" name="grade_id" value="" id="" />
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Subjects</button>
                                </form>
                            </div>
                          <!-- End -->
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