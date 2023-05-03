@if ($payment_schedule == 'false')
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@else
    @if ($payment_schedule['payment_schedules'])
        @foreach ($payment_schedule['payment_schedules'] as $key => $schedule)
            @php
                if($key == 0){
                    $amount = $payment_schedule['assessment_exam']->downpayment;
                }else{
                    $exam = 'exam'.$key;
                    $amount = $payment_schedule['assessment_exam']->$exam;
                }
            @endphp
            <div class="row  align-items-end">
                <div class="col-md-7">
                    <div class="form-group mb-1">
                        <label for="term" class="m-0 font-weight-bold text-primary">{{ $schedule->description }}</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <label for="term" class="m-0 font-weight-bold text-black">:</label>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-1 right">
                        <label for="term" class="m-0 font-weight-bold text-black">{{ number_format($amount, 2) }}</label>
                    </div>
                </div>
            </div>
        @endforeach
        <p></p>
        <div class="row  align-items-end">
            <div class="col-md-7">
                <div class="form-group mb-1">
                    <label for="term" class="m-0 font-weight-bold text-primary">TOTAL ASSESSED</label>
                </div>
            </div>
            <div class="col-md-1">
                <label for="term" class="m-0 font-weight-bold text-black">:</label>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-1 right">
                    <label for="term" class="m-0 font-weight-bold text-black">{{ number_format($payment_schedule['assessment_exam']->amount, 2) }}</label>
                </div>
            </div>
        </div>
        <div class="row  align-items-end">
            <div class="col-md-10">
                <div class="form-group mb-1">
                    <select id="payment_schedule" class="form-control select clearable">
                        @foreach ($payment_schedule['payment_schedules'] as $key => $schedule)
                            <option value="{{ $key }}">{{ $schedule->description }}</option>
                        @endforeach
                    </select>
                   
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-1">
                    <input class="" id="nograde" type="checkbox" value="1" name="nograde" {{ (old('nograde')) ? 'checked' : '' }} >
                </div>
            </div>
        </div>
        <div class="row  align-items-end">
            <div class="col-md-12">
                <div class="form-group mb-1">
                   <h3 class="mid text-black">DOWNPAYMENT</h3>
                </div>
            </div>
        </div>
        <div class="row  align-items-end">
            <div class="col-md-5">
                <div class="form-group mb-1">
                   <h3 class="mid text-black font-weight-bold">DUE</h3>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group mb-1">
                   <h3 class="mid font-weight-bold text-danger">10,000.50</h3>
                </div>
            </div>
        </div>
    @endif
@endif