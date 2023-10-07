<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Student Adjustment</h6>
            </div>
            <div class="card-body" id="">
                <form method="POST" action=""  role="form" id="form_studentadjustment">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="term" class="m-0 font-weight-bold text-primary">* Date</label>
                                <input type="text" name="created_at" required id="created_at" class="datepicker form-control text-uppercase clearable text-uppercase" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="">
                                <div id="error_created_at" class="errors"></div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="term" class="m-0 font-weight-bold text-primary">* Particular</label>
                                <input type="text" name="particular" required id="particular" class="form-control text-uppercase clearable text-uppercase" value="" placeholder="">
                                <div id="error_particular" class="errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" id="button_group">
                                <label for="term" class="m-0 font-weight-bold text-primary">* Type</label>
                                <select name="type" class="form-control" id="type" required>
                                    <option value="1">CREDIT</option>
                                    <option value="2">DEBIT</option>
                                    <option value="3">REFUND</option>
                                </select>
                                <div id="error_type" class="errors"></div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group" id="button_group">
                                <label for="term" class="m-0 font-weight-bold text-primary">* Amount</label>
                                <input type="text" name="amount" id="amount" placeholder="0.00" required class="form-control text-uppercase clearable" value="">
                                <div id="error_amount" class="errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group right" id="button_group">
                                <button type="submit" id="save_adjustment" class="btn btn-sm btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span class="text">Save</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>