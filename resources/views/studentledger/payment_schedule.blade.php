@if (!isset($payment_schedule) || $payment_schedule == 'false')
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@else
    @if ($payment_schedule['payment_schedules']->isNotEmpty() && $payment_schedule['assessment_exam'])
        @php
            $pay_period = (Auth::user()->paymentperiod) ? ((Auth::user()->paymentperiod->pay_period > max(array_keys($payment_schedule['payment_schedules']->toArray()))) ? max(array_keys($payment_schedule['payment_schedules']->toArray())) : Auth::user()->paymentperiod->pay_period) : 0;
        @endphp
        @foreach ($payment_schedule['payment_schedules'] as $key => $schedule)
            @php
                if($key == 0){
                    $amount = $payment_schedule['assessment_exam']['downpayment'];
                }else{
                    $exam = 'exam'.$key;
                    $amount = $payment_schedule['assessment_exam'][$exam];
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
                    <label for="term" class="m-0 font-weight-bold text-black">{{ number_format($payment_schedule['assessment_exam']['amount'], 2) }}</label>
                </div>
            </div>
        </div>
        <div class="row  align-items-end">
            <div class="col-md-7">
                <div class="form-group mb-1">
                    <label for="term" class="m-0 font-weight-bold text-primary">DEBIT ADJUSTMENT</label>
                </div>
            </div>
            <div class="col-md-1">
                <label for="term" class="m-0 font-weight-bold text-black">:</label>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-1 right">
                    <label for="term" class="m-0 font-weight-bold text-black">{{ number_format($payment_schedule['debit'], 2) }}</label>
                </div>
            </div>
        </div>
        <div class="row  align-items-end">
            @if (isset($with_checkbox) && $with_checkbox == true)
                <div class="col-md-10">
                    <div class="form-group mb-1">
                        <select id="pay_period" class="form-control select clearable">
                            @foreach ($payment_schedule['payment_schedules'] as $key => $schedule)
                                <option value="{{ $key }}" {{ ($key == $pay_period) ? 'selected' : '' }}>{{ $schedule->description }}</option>
                            @endforeach
                        </select>
                    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-1">
                        <input class="" id="pay_period_default" type="checkbox" value="1" {{ ((Auth::user()->paymentperiod)) ? 'checked' : '' }}>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="form-group mb-1">
                        <select id="pay_period" class="form-control select clearable">
                            @foreach ($payment_schedule['payment_schedules'] as $key => $schedule)
                                <option value="{{ $key }}" {{ ($key == $pay_period) ? 'selected' : '' }}>{{ $schedule->description }}</option>
                            @endforeach
                        </select>
                    
                    </div>
                </div>
            @endif
        </div>
        <div class="row  align-items-end">
            <div class="col-md-12">
                <div class="form-group mb-1">
                   <h3 class="mid text-black" id="pay_period_text">{{ ($payment_schedule['payment_schedules']) ? $payment_schedule['payment_schedules'][$pay_period]->description : 'No Payment Schedule' }}</h3>
                </div>
            </div>
        </div>
        @php
            $balance_due = 0;

            if(max(array_keys($payment_schedule['payment_schedules']->toArray())) ==  $pay_period)
            {   
                $total_payables = $payment_schedule['assessment_exam']['amount']+$payment_schedule['debit'];
                $balance_due = $total_payables-str_replace('-', "", $payment_schedule['credit']);
            }else{
                $rembal = 0;

                for($i=0; $i <= $pay_period; $i++)
                {
                    if($i == 0)
                    {
                        $rembal += $payment_schedule['assessment_exam']['downpayment'];
                    }else{
                        $exam = 'exam'.$i;
                        $rembal += $payment_schedule['assessment_exam'][$exam];
                    }
                }

                $balance = ($rembal+$payment_schedule['debit'])-str_replace('-', "", $payment_schedule['credit']);

                $balance_due = ($balance < 0) ? 0 : $balance;
            }
        @endphp
        <div class="row  align-items-end">
            <div class="col-md-5">
                <div class="form-group mb-1">
                   <h3 class="mid text-black font-weight-bold">DUE</h3>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group mb-1">
                   <h3 class="mid font-weight-bold text-danger" id="balance_due">{{ number_format($balance_due,2) }}</h3>
                </div>
            </div>
        </div>
    @else
        <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
    @endif
@endif