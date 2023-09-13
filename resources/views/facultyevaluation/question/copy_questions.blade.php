xxxx <!-- Logout Modal-->
<div class="modal fade" id="copyQuestionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-m" role="document">
    <div class="modal-content">
        <div class="modal-header">
           <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Copy Survey Questions</h1>
           <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
           </button>
        </div>
        <div class="modal-body">
           <div class="container py-0 px-0">       
               <div class="row">
                   <div class="col-lg-12 mx-auto">
                       <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                           <p class="mb-0">Copy survey questions from other educational levels</p>
                           <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                           <!-- credit card info-->
                           <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action="{{ route('savecopyquestion') }}" id="form_copyquestions" role="form">
                                    @csrf
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="educational_level_id" class="m-0 font-weight-bold text-primary">* Copy From</label>
                                        </div>
                                        <div class="col-md-9">
                                            @include('partials.educlevels.dropdown', ['fieldname' => 'copy_from', 'fieldid' => 'copy_from'])
                                            <div id="error_copy_from" class="errors"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="educational_level_id" class="m-0 font-weight-bold text-primary">* Copy To</label>
                                        </div>
                                        <div class="col-md-9">
                                            @include('partials.educlevels.dropdown', ['fieldname' => 'copy_to', 'fieldid' => 'copy_to'])
                                            <div id="error_copy_to" class="errors"></div>
                                        </div>
                                    </div>
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Copy Survey Questions</button>
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