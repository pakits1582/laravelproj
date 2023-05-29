 <!-- Logout Modal-->
 <div class="modal" id="isseudby_modal" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add Issueing Office</h1>
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
                                    <form method="POST" data-action="{{ route('saveissuedby') }}" id="addissuedby_form" role="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="code" class="m-0 font-weight-bold text-primary">* Code</label>
                                        <input type="text" name="code" placeholder="" class="form-control text-uppercase" value="{{ old('code') }}">
                                        <div id="error_code"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="m-0 font-weight-bold text-primary">* Name</label>
                                        <input type="text" name="name" placeholder="" class="form-control text-uppercase" value="{{ old('name') }}">
                                        <div id="error_name"></div>
                                    </div>
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Issueing Office</button>
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