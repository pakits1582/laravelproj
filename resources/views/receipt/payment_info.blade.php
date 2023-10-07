<form method="POST" action=""  role="form" id="form_payment">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="row align-items-start">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="term" class="m-0 font-weight-bold text-primary">Bank Name</label>
                        <select name="bank_id" class="form-control select bank_clearable" id="bank_id">
                            <option value="">- search bank -</option>
                            @if ($banks)
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            @endif
                            <option value="add_bank">- Click to add new bank -</option>  
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="check_no" class="m-0 font-weight-bold text-primary">Check No.</label>
                        <input type="text" name="check_no" id="check_no" class="form-control text-uppercase bank_clearable" value="" placeholder="">
                        <div id="error_check_no"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="deposit_date" class="m-0 font-weight-bold text-primary">Deposit Date</label>
                        <input type="text" name="deposit_date" id="deposit_date" class="form-control text-uppercase bank_clearable datepicker" value="" placeholder="">
                        <div id="error_deposit_date"></div>
                    </div>
                </div>
                <div class="col-md-4 mid">
                    <div class="form-group" id="button_group">
                        <button type="button" id="add_fee" class="btn btn-sm btn-success btn-icon-split mb-0">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">(F2) Add</span>
                        </button>
                        <button type="button" id="delete_fee" class="btn btn-sm btn-danger btn-icon-split actions mb-0" disabled>
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive-sm">
                        <label for="term" class="m-0 font-weight-bold text-primary">Fees Selected</label>
                        <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                            <thead class="">
                                <tr>
                                    <th class="w30"></th>
                                    <th class="w150">Code</th>
                                    <th class="">Description</th>
                                    <th class="w200">Type</th>
                                    <th class="w150">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="text-black" id="feestobepayed">
                                <tr id="norecord"><td class="mid" colspan="5">No fees selected</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="right"><h2 class="m-0">TOTAL</h1></td>
                                    <td>
                                        <input type="text" name="total_amount" id="total_amount" required class="biginput form-control text-uppercase clearable" readonly value="" placeholder="">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="cancel_receipt"  class="m-0 font-weight-bold text-primary checkbox-inline">
                        <input type="checkbox" class="checkbox" value="1" id="cancel_receipt"> Cancel Transaction</label> | 
                        <a href="#" id="reprint_receipt" class="pointer"><span class="m-0 font-weight-bold text-primary checkbox-inline">Re-print Receipt</span></a>
                </div>
                <div class="col-md-6 right">
                    <div class="form-group" id="button_group">
                        <button type="submit" id="save_payment" class="btn btn-sm btn-success btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Save Payment</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex flex-column">
            <label for="code" class="m-0 font-weight-bold text-primary">Payment Schedule</label>
            <div id="payment_schedule" class="border border-primary p-2"></div>
        </div>
    </div>
</form>
