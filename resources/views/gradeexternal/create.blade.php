 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div id="modal_dropdown"></div>
 <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add External Grade</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container py-0 px-0">       
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" accept="route('gradeexternals.store')" id="add_external_grade_form" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">* School</label>
                                    <select name="school_id" class="form-control" id="school">
                                        <option value="">- select school -</option>
                                        @if ($schools)
                                            @foreach ($schools as $school)
                                                <option value="{{ $school->id }}" 
                                                    {{ (old('school') === $school->id) ? 'selected' : '' }}
                                                    >( {{ $school->code }} ) {{ $school->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">* Program</label>
                                    <select name="program_id" class="form-control" id="program_id">
                                        <option value="">- select program -</option>
                                        @if ($programs)
                                            @foreach ($programs as $program)
                                                <option value="{{ $program->id }}" 
                                                    {{ (old('program') === $program->id) ? 'selected' : '' }}
                                                    >( {{ $program->code }} ) {{ $program->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="m-0 font-weight-bold text-primary">* Subject Code</label>
                                    <input type="text" name="name" id="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name" class="m-0 font-weight-bold text-primary">Subject Description</label>
                                    <input type="text" name="name" id="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="units" class="m-0 font-weight-bold text-primary">Grade</label>
                                                <input type="text" name="units" placeholder="" class="form-control text-uppercase" value="{{ old('units') }}">
                                                @error('units')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="tfunits" class="m-0 font-weight-bold text-primary">Units</label>
                                                <input type="text" name="tfunits" placeholder="" class="form-control text-uppercase" value="{{ old('tfunits') }}">
                                                @error('tfunits')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="loadunits" class="m-0 font-weight-bold text-primary">C. G.</label>
                                                <input type="text" name="loadunits" placeholder="" class="form-control text-uppercase" value="{{ old('loadunits') }}">
                                                @error('loadunits')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Remark</label>
                                                <select name="remark_id" class="form-control" id="remark">
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
                                                @error('remark_id')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="lecunits" class="m-0 font-weight-bold text-primary">Equivalent</label>
                                                <input type="text" name="lecunits" placeholder="" class="form-control text-uppercase" value="{{ old('lecunits') }}">
                                                @error('lecunits')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="save_compounded_fee" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Fee</button>
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