<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Remove Post Charge</h6>
            </div>
            <div class="card-body" id="">
                <div class="form-group" id="">
                    <h6 class="m-0 font-weight-bold text-primary">Fee to be removed</h6>
                    <p class="font-italic text-info text-small">Note: After selecting fee, all students charged of the fee will be displayed.</p>
                    <div class="row align-items-center mb-1">
                        <div class="col-md-1">
                            <label for="term" class="m-0 font-weight-bold text-primary">Fee</label>
                        </div>
                        <div class="col-md-11">
                            <select name="fee_id" class="form-control additional_fee" id="fee_to_remove">
                                <option value="">- select additional fee -</option>
                                @if ($additionalfees)
                                    @foreach ($additionalfees as $additionalfee)
                                        <option value="{{ $additionalfee->id }}">{{ $additionalfee->code }} - {{ $additionalfee->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group right" id="button_group">
                    <button type="button" id="remove_postcharge" class="btn btn-sm btn-danger btn-icon-split actions">
                        <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Remove Post Charge</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>