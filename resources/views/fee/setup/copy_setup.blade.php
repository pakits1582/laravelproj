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
                                <div class="col-md-12">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Assessment Fees</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="return_copy_setup_fees">
                                                @include('fee.setup.return_copy_setup')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Copy Assessment Fees</button>
                        </form>
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