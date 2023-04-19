<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add Post Charge</h6>
            </div>
            <div class="card-body" id="">
                <form method="POST" action=""  role="form" id="form_addpostcharge">
                    @csrf
                    <div class="row px-2">
                        <div class="col-md-6 p-0">
                            <div class="form-group">
                                <h6 class="m-0 font-weight-bold text-primary">Students to be charged</h6>
                                <p class="font-italic text-info text-small">Note: Click on checkbox to select student to be charged.</p>
                                <div class="col-xs-8 col-xs-offset-2 well">
                                    <table class="table table-scroll table-sm table-striped table-bordered text-medium" style="">
                                        <thead>
                                            <tr>
                                                <th class="w30 mid"><input type="checkbox" name="" id="checkallcheckbox"></th>
                                                <th class="w50">#</th>
                                                <th class="w100">ID No.</th>
                                                <th>Student Name</th>
                                                <th class="w100">Crs & Yr</th>
                                            </tr>
                                        </thead>
                                        <tbody id="return_filtered_students" class="border">
                                            <tr>
                                                <td class="mid" colspan="5">No records to be displayed</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="postcharge_fees">
                                <h6 class="m-0 font-weight-bold text-primary">Fees to be charged</h6>
                                <p class="font-italic text-info text-small">Note: You can select multiple fee by clicking the (+) button.</p>
                                <div class="row align-items-center mb-1">
                                    <div class="col-md-1">
                                        <label for="term" class="m-0 font-weight-bold text-primary">Fee</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="fees[]" class="form-control additional_fee" id="additional_fees" required>
                                            <option value="">- select additional fee -</option>
                                            @if ($additionalfees)
                                                @foreach ($additionalfees as $additionalfee)
                                                    <option value="{{ $additionalfee->id }}">{{ $additionalfee->code }} - {{ $additionalfee->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="amount[]" id="" class="fee_amount form-control" placeholder="Amount" pattern="^[0-9]+(?:\.[0-9]{1,2})?$" title="CDA Currency Format - no currency sign and no comma(s) - cents (.##) are optional" autocomplete="off">
                                    </div>
                                    <div class="col-md-1">
                                        <a href="#" id="add_fee" class="btn btn-primary btn-circle btn-sm" title="Add Fee">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                            <h3 class="right text-black">TOTAL FEE: <span id="totalfee">0.00</span></h3>
                            <div class="form-group right" id="button_group">
                                <button type="submit" id="save_grant" class="btn btn-success btn-icon-split actions">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span class="text">Save Post Charge</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>