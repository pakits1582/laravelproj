 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-m" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add New Term</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container py-0 px-0">       
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                            <p class="mb-0">Add new record in the database</p>
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" data-action="{{ route('saveterm') }}" id="addterm_form" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">* Term</label>
                                    <input type="text" name="term" placeholder="" class="form-control" value="{{ old('term') }}">
                                    <div id="error_term"></div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="code"  class="m-0 font-weight-bold text-primary">* Type</label>
                                        </div>
                                        <div class="col-md-9">
                                            <label for="regular"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="type" value="1" id="regular" checked> Regular Term </label>
                                            <label for="short"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="type" value="2" id="short"> Short Term </label>
                                        </div>
                                    </div>
                                    <div id="error_type"></div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for=""  class="m-0 font-weight-bold text-primary">* Source</label>
                                        </div>
                                        <div class="col-md-9">
                                            <label for="term_internal"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="source" value="1" id="term_internal" checked> Internal </label>
                                            <label for="term_external"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="source" value="2" id="term_external"> External </label>    
                                        </div>
                                    </div>
                                    <div id="error_source"></div>
                                </div>
                                <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Term</button>
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