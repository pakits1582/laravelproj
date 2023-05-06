 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add Fee to Pay</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container py-0 px-0">       
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
                                <form method="POST" id="addpaymentfee_form" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="bank" class="m-0 font-weight-bold text-primary">* Fee</label>
                                    <select name="fee_id" class="form-control select clearable" id="fee_id" required>
                                        <option value="">- select fee -</option>
                                        @if ($fees)
                                            @foreach ($fees as $fee)
                                                <option value="{{ $fee->id }}" {{ ($default_fee) ? ($default_fee->id == $fee->id ? 'selected' : '') : '' }}>{{ $fee->code }} - {{ $fee->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <p class="font-italic text-info">Selected Fee Details</p>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_code" class="m-0 font-weight-bold text-primary">Fee Code</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="fee_code" id="fee_code" value="{{ ($default_fee) ? $default_fee->code  : '' }}" class="form-control text-uppercase mediuminput">
                                            @error('fee_code')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_name" class="m-0 font-weight-bold text-primary">Fee Name</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="fee_name" id="fee_name" value="{{ ($default_fee) ? $default_fee->name  : '' }}" class="form-control text-uppercase mediuminput">
                                            @error('fee_name')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_type" class="m-0 font-weight-bold text-primary">Fee Type</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="fee_type" value="{{ ($default_fee) ? $default_fee->feetype->type  : '' }}" id="fee_type" class="form-control text-uppercase mediuminput">
                                            @error('fee_type')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_amount" class="m-0 font-weight-bold text-primary">* Amount</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="fee_amount" id="fee_amount" value="{{ ($default_fee) ? ($default_fee->default_value > 0 ? $default_fee->default_value : '') : '' }}" class="form-control biginput" pattern="^[0-9]+(?:\.[0-9]{1,2})?$" title="CDA Currency Format - no currency sign and no comma(s) - cents (.##) are optional" required autofocus="autofocus">
                                            @error('amount')
                                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="selectedfeeshidden">
                                    <input type="hidden" name="feeselected" 
                                        value="{{ ($default_fee) ? $default_fee->amount  : '' }}" 
                                        id="{{ ($default_fee) ? $default_fee->id  : '' }}"
                                        data-feecode="{{ ($default_fee) ? $default_fee->code  : '' }}" 
                                        data-feedesc="{{ ($default_fee) ? $default_fee->name  : '' }}" 
                                        data-type="{{ ($default_fee) ? $default_fee->feetype->type  : '' }}" 
                                        data-inassess="{{ ($default_fee) ? $default_fee->feetype->inassess  : '' }}" 
                                    />
                                </div>
                                <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Add Fee</button>
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