 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Compound Fees</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container py-0 px-0">       
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                            <p class="font-italic text-info">Only non-assessed type of fees can be compounded.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" id="compoundfee_form" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">Select Fee</label>
                                    <select name="non_assess_fees" class="form-control" id="non_assess_fees">
                                        <option value="">- select fee -</option>
                                        @if ($fees)
                                            @foreach ($fees as $fee)
                                                <option 
                                                    value="{{ $fee->id }}"
                                                >{{ $fee->code.' - '.$fee->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="add_fee" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Add Fee</button>
                                    <p class="font-italic text-info">Click Add Fee button to add to compounded fees below.</p>
                                </div>
                                <div class="form-group">
                                    <label for="term" class="m-0 font-weight-bold text-primary">* Compounded Fees</label>
                                    <textarea name="compoundedfees" id="compoundedfees" readonly class="form-control" rows="3"></textarea>
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