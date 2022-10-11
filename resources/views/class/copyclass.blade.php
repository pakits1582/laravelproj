 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Copy Class Subjects from Another Section</h1>
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
                                <div class="bg-white rounded-lg shadow-sm p-3">
                                    <p class="font-italic text-info">Note: You can view previous section class offerings by selecting section and period below.</p>
                                    <!-- credit card info-->
                                    <div id="nav-tab-card" class="tab-pane fade show active">
                                        @if(Session::has('message'))
                                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                        @endif
                                        <form method="POST" action="{{ route('classes.storecopyclass', ['section' => $section->id]) }}"  role="form" id="form_copyclass">
                                            @csrf
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">Section</label>
                                                        <input type="hidden" name="section_copyto" value="{{ $section->id }}" id="section_copyto" />
                                                        <input type="hidden" name="period_copyto" value="{{ session('current_period') }}" id="period_copyto" />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select name="section_copyfrom" class="form-control copyclass_dropdown" id="section_copyfrom" required>
                                                            <option value="">- select section -</option>
                                                            @if ($sections)
                                                                @foreach ($sections as $key => $section)
                                                                    <option value="{{ $section->id }}" >{{ $section->code }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="name" class="m-0 font-weight-bold text-primary">Period</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        @include('partials.periods.dropdown', ['fieldname' => 'period_copyfrom', 'fieldid' => 'period_copyfrom', 'fieldclass' => 'copyclass_dropdown'])                                                
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group">
                                                <div class="row"> --}}
                                                    <div class="table-responsive-sm">
                                                        <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                                            <thead class="">
                                                                <tr>
                                                                    <th class="w200">Section</th>
                                                                    <th class="w200 mid">Subject</th>
                                                                    <th>Description</th>
                                                                    <th class="w40 mid">Units</th>
                                                                    <th class="w35 mid">TF</th>
                                                                    <th class="w50 mid">Load</th>
                                                                    <th class="w35 mid">Lec</th>
                                                                    <th class="w35 mid">Lab</th>
                                                                    <th class="w35 mid">Hrs</th>
                                                                    <th class="w40">Slots</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-black" id="return_copy_classsubjects">
                                                                <tr><td class="mid" colspan="13">No records to be displayed!</td></tr>
                                                            </tbody>
                            
                                                        </table>
                                                    </div>
                                                {{-- </div>
                                            </div> --}}
                                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Copy Subjects</button>
                                        </form>
                                    </div>
                                  <!-- End -->
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