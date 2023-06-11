 <!-- Logout Modal-->
 <div class="modal fade" id="other_assignments_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
     <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Faculty Other Assignments</h1>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="container py-0 px-0 mb-3">       
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                            {{-- <p class="mb-0">Add new record in the database</p>
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p> --}}
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" id="form_otherassignments" role="form">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <label for="fee_code" class="m-0 font-weight-bold text-primary">&nbsp;&nbsp;Period</label>
                                            </div>
                                            <div class="col-md-10">
                                                <h6 class="m-0 font-weight-bold text-black">{{ $period->name }}</h6>
                                                <input type="hidden" name="period_id" id="period_id" value="{{ $period->id }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <label for="fee_code" class="m-0 font-weight-bold text-primary">* Instructor</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="instructor_id" class="form-control select clearable" id="instructor_id" required>
                                                    <option value="">- select instructor -</option>
                                                    @if ($instructors)
                                                        @foreach ($instructors as $instructor)
                                                            <option value="{{ $instructor->id }}">{{ $instructor->last_name.', '.$instructor->first_name.' '.$instructor->middle_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {{-- @error('instructor_id')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror --}}
                                                <div id="error_instructor_id"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="font-italic text-info">Other Assignment Details</p>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="assignment" class="m-0 font-weight-bold text-primary">* Assignment</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" name="assignment" id="assignment" value="{{ old('assignment') }}" class="form-control text-uppercase" required>
                                                {{-- @error('assignment')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror --}}
                                                <div id="error_assignment"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="units" class="m-0 font-weight-bold text-primary">* Load Units</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" name="units" id="units" value="{{ old('units') }}" class="form-control text-uppercase" required>
                                                {{-- @error('units')
                                                    <p class="text-danger text-xs mt-1">{{$message}}</p>
                                                @enderror --}}
                                                <div id="error_units"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Other Assignment</button>
                                </form>
                            </div>
                            <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-1">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List of Faculty Other Assignments</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped table-bordered mb-0" style="font-size: 14px;">
                        <thead class="">
                            <tr>
                                <th class="w40">#</th>
                                <th class="">Assignment</th>
                                <th class="w100">Units</th>
                                <th class="w100">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-black" id="return_otherassignments">
                             <tr><td colspan="4" class="mid">No records to be displayed!</td></tr>
                        </tbody>
                    </table>
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