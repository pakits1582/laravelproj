 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-m" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add New Bank</h1>
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
                                <form method="POST" data-action="{{ route('savebank') }}" id="addbank_form" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="bank" class="m-0 font-weight-bold text-primary">* Bank</label>
                                    <input type="text" name="bank" placeholder="" class="form-control uppercase" value="{{ old('bank') }}">
                                    <div id="error_bank"></div>
                                </div>
                                <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Bank</button>
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