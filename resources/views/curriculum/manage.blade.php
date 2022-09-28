@extends('layout')
@section('title') {{ 'Curriculum Management' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Curriculum Management</h1>
        <p class="mb-4">Manage curriculum subjects, pre-requisites, etc.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary">( {{ $program->code }} ) {{ $program->name }}</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <h1 class="h3 mb-0 text-primary font-weight-bold">Add Curriculum Subjects</h1>
                            <p class="mb-2">Add new subjects in the curriculum</p>
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action="{{ route('curriculum.storesubjects') }}"  role="form" id="form_addsubjectincurriculum">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Curriculum</label>
                                                <select name="curriculum" class="form-control" id="curriculum">
                                                    <option value="">- select curriculum -</option>
                                                    @if ($program->curricula)
                                                        @foreach ($program->curricula as $curriculum)
                                                            <option 
                                                                value="{{ $curriculum->id }}" 
                                                                data-type="{{ $curriculum->curriculum }}"
                                                                {{ (old('curriculum') == $curriculum->id) ? 'selected' : '' }}
                                                            >{{ $curriculum->curriculum }}</option>
                                                        @endforeach
                                                    @endif
                                                    <option value="addcurriculum" data-toggle="modal" data-target="#modal">- Click to add new curriculum -</option>  
                                                </select>
                                                <div id="error_curriculum_id"></div>
                                                @error('curriculum_id')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Term</label>
                                                <select name="term" class="form-control" id="term">
                                                    <option value="">- select term -</option>
                                                    @if ($terms)
                                                        @foreach ($terms as $term)
                                                            <option 
                                                                value="{{ $term->id }}" 
                                                                data-type="{{ $term->type }}"
                                                                {{ (old('term') == $term->id) ? 'selected' : '' }}
                                                            >{{ $term->term }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_term_id"></div>
                                                @error('term_id')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="year_level" class="m-0 font-weight-bold text-primary">* Year Level</label>
                                                <select name="year_level" class="form-control">
                                                    <option value="">- select year level -</option>
                                                    <option value="1" {{ (old('year_level') == 1) ? 'selected' : '' }}>First Year</option>
                                                    <option value="2" {{ (old('year_level') == 2) ? 'selected' : '' }}>Second Year</option>
                                                    <option value="3" {{ (old('year_level') == 3) ? 'selected' : '' }}>Third Year</option>
                                                    <option value="4" {{ (old('year_level') == 4) ? 'selected' : '' }}>Fourth Year</option>
                                                    <option value="5" {{ (old('year_level') == 5) ? 'selected' : '' }}>Fifth Year</option>
                                                    <option value="6" {{ (old('year_level') == 6) ? 'selected' : '' }}>Sixth Year</option>
                                                </select>
                                                <div id="error_year_level"></div>
                                                @error('year_level')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="name" class="m-0 font-weight-bold text-primary">Search Subject</label>
                                                <input type="text" id="search_subject" class="form-control text-uppercase" value="" placeholder="Type here to search subject">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="year_level" class="m-0 font-weight-bold text-primary">Search Result</label>
                                                    <p class="font-italic text-info">Note: (*)  (Units) [Subject Code] -[Description]. CTRL + click for multiple selection</p>
                                                    <select  class="form-control select currsubadding" id="search_result" multiple size="15">
                                                        @if ($subjects)
                                                            @foreach ($subjects as $subject)
                                                                <option value="{{ $subject->id }}" id="option_{{ $subject->id }}">({{ $subject->units }}) - [ {{ $subject->code }} ] {{ $subject->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mid  my-auto">
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-primary btn-icon-split" id="button_moveright">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-plus-square"></i>
                                                        </span>
                                                        <span class="text">==></span>
                                                    </a>
                                                    <a href="#" class="btn btn-primary btn-icon-split" id="button_moveleft">
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
                                                    <p class="font-italic text-info">Note: (*)  (Units) [Subject Code] -[Description]. CTRL + click for multiple selection</p>
                                                    <select name="subjects[]" class="form-control select currsubadding" id="selected_subjects" multiple size="15">
                                                        <option value="">- Add at least one subject -</option>
                                                    </select>
                                                    <p class="font-italic text-info">Note: (*)  Only highlighted subjects will be added. Please double check selection.</p>
                                                    <div id="error_subjects"></div>
                                                    @error('subjects')
                                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="program_id" id="program_id" value="{{ $program->id }}" />
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Subjects</button>
                                </form>
                            </div>
                          <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- DISPLAY CURRICULUM HERE --}}
        <div id="view_curriculum">
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection