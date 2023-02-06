 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Copy Setup Fee From Previous Term</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            {{-- <div class=" py-0 px-3">  --}}
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Copy setup fees to period ({{ $period->code }}) {{ $period->name }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-lg-12 mx-auto"> --}}
                                {{-- <div class="bg-white rounded-lg shadow-sm p-3"> --}}
                                    <!-- credit card info-->
                                    <div id="nav-tab-card" class="tab-pane fade show active">
                                        @if(Session::has('message'))
                                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                        @endif
                                        <form method="POST" action=""  role="form" id="form_copyclass">
                                            @csrf
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <div class="col-md-1 mid">
                                                        <label for="code"  class="m-0 font-weight-bold text-primary">Period</label>
                                                        <input type="hidden" name="period_copyto" value="{{ $period->id }}" id="period_copyto" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="period_copyfrom" class="form-control" id="period_copyfrom" required>
                                                            <option value="">- select period -</option>
                                                            @if ($periods)
                                                                @foreach ($periods as $period)
                                                                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <p class="font-italic text-info mb-0">Note: You can view previous setup fees by selecting period.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 mx-auto">
                                                    <div class="card shadow mb-4">
                                                        <div class="card-header py-3">
                                                            <h6 class="m-0 font-weight-bold text-primary">Assessment Fees</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            {{-- <div class="table-responsive-sm">
                                                                <table class="table table-sm table-striped table-bordered" style="font-size: 14px;"> --}}
                                                            <div class="table-responsive-sm col-xs-8 col-xs-offset-2 well">
                                                                <table class="table table-sm table-scroll table-striped table-bordered" style="font-size: 14px;">
                                                                    <thead class="">
                                                                        <tr>
                                                                            <th class="w30 mid">
                                                                                <input type="checkbox" id="check_all" />
                                                                            </th>
                                                                            <th class="w100">Level</th>
                                                                            <th class="w100">College</th>
                                                                            <th class="w100">Program</th>
                                                                            <th class="w40">Yr.</th>
                                                                            <th class="w40">Nw</th>
                                                                            <th class="w40">Old</th>
                                                                            <th class="w40">Sex</th>
                                                                            <th class="w40">Crs</th>
                                                                            <th class="w40">Trns</th>
                                                                            <th class="w40">PS</th>
                                                                            <th class="w150">Subject</th>
                                                                            <th class="">Fee</th>
                                                                            <th class="">Fee Type</th>
                                                                            <th class="w70">Rate</th>
                                                                            <th class="w70">Schm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="text-black" id="return_copy_setup_fees">
                                                                        <tr><td colspan="16" class="mid">No records to be displayed!</td></tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Copy Assessment Fees</button>
                                        </form>
                                    </div>
                                  <!-- End -->
                                {{-- </div> --}}
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
         </div>
         <div class="modal-footer">
            {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a> --}}
         </div>
     </div>
 </div>
</div>