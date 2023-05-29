 <!-- Logout Modal-->
<div class="modal" id="soresolution_modal" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add S.O./Resolution</h1>
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
                                    <form method="POST" data-action="{{ route('savesoresolution') }}" id="addsoresolution_form" role="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="conjunction" class="m-0 font-weight-bold text-primary">* Conjuction</label>
                                        <input type="text" name="conjunction" placeholder="" class="form-control text-uppercase" value="{{ old('conjunction') }}">
                                        <div id="error_conjunction"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="title" class="m-0 font-weight-bold text-primary">* Title</label>
                                        <input type="text" name="title" placeholder="" class="form-control text-uppercase" value="{{ old('title') }}">
                                        <div id="error_title"></div>
                                    </div>
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save S.O./Resolution</button>
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