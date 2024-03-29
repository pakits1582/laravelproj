 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add Multiple Subjects Offering</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-3"> 
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $section->programinfo->name }} (SECTION: {{ $section->name }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mx-auto">
                                {{-- <div class="bg-white rounded-lg shadow-sm pt-0 p-3"> --}}
                                    <p class="font-italic text-info">Note: You can select curriculum, term and year level to filter subjects to be displayed in the result set.</p>
                                    <!-- credit card info-->
                                    <div id="nav-tab-card" class="tab-pane fade show active">
                                        @if(Session::has('message'))
                                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                        @endif
                                        <form method="POST" action="{{ route('classes.store') }}"  role="form" id="form_addsubject_class_offering">
                                            @csrf
                                            <div class="form-group">
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-1">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">Curriculum</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select name="curriculum" class="form-control filter_curriculum_subjects" id="curriculum">
                                                            <option value="">- select curriculum -</option>
                                                            @if ($section)
                                                                @foreach ($section->programinfo->curricula as $key => $curriculum)
                                                                    <option value="{{ $curriculum->id }}" @if ($loop->first){{ 'selected' }} @endif>{{ $curriculum->curriculum }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 right">
                                                        <label for="name" class="m-0 font-weight-bold text-primary">Term</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="term" class="form-control filter_curriculum_subjects" id="term">
                                                            <option value="">- select term -</option>
                                                                @if ($terms)
                                                                    @foreach ($terms as $term)
                                                                        <option value="{{ $term->id }}" {{ (Session::get('periodterm') === $term->id) ? 'selected' : '' }}>{{ $term->term }}</option>
                                                                    @endforeach
                                                                @endif
                                                        </select>                                                    
                                                    </div>
                                                    <div class="col-md-2 right">
                                                        <label for="name" class="m-0 font-weight-bold text-primary">Year Level</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select name="year_level" class="form-control filter_curriculum_subjects" id="yearlevel">
                                                            <option value="">- select year level -</option>
                                                            @if ($section)
                                                                @for ($i=1; $i <= $section->programinfo->years; $i++)
                                                                    <option value="{{ $i }}" {{ ($section->year === $i) ? 'selected' : '' }}>{{ Helpers::yearLevel($i) }}</option>
                                                                @endfor
                                                            @endif
                                                        </select>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="year_level" class="m-0 font-weight-bold text-primary">Curriculum Subjects</label>
                                                            <p class="font-italic text-info">Note: CTRL + click for multiple selection</p>
                                                            <select  class="form-control select currsubadding" id="search_result_offering" multiple size="10">
                                                                @if ($curriculum_subjects)
                                                                    @foreach ($curriculum_subjects as $curr_subject)
                                                                        <option value="{{ $curr_subject->id }}" id="option_{{ $curr_subject->id }}" title="{{ $curr_subject->subjectinfo->name }}">({{ $curr_subject->subjectinfo->units }}) - [ {{ $curr_subject->subjectinfo->code }} ] {{ $curr_subject->subjectinfo->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 mid  my-auto">
                                                        <div class="form-group">
                                                            <a href="#" class="btn btn-primary btn-icon-split mb-2" id="button_moveright_offering">
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-plus-square"></i>
                                                                </span>
                                                                <span class="text">==></span>
                                                            </a>
                                                            <a href="#" class="btn btn-primary btn-icon-split mb-2" id="button_moveleft_offering">
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
                                                            <select name="subjects[]" class="form-control select currsubadding" id="selected_subjects_offering" multiple size="10">
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
                                            <input type="hidden" name="section" value="{{ $section->id }}" id="section_id" />
                                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Subjects</button>
                                        </form>
                                    </div>
                                  <!-- End -->
                                {{-- </div> --}}
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