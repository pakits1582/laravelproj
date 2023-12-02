@if ($previous_balances)
<div class="table-responsive" id="table_data">
    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
        <thead class="">
            <tr>
                <th class="w150">Period Code</th>
                <th class="">Period Name</th>
                <th class="w150">Total Payables</th>
                <th class="w150">Total Payments</th>
                <th class="w150">Balance</th>
                @if (isset($forwardable) && $forwardable == true)
                    <th class="w250">Action</th>
                @endif
                    
            </tr>
        </thead>
        <tbody class="text-black" id="">
            @foreach ($previous_balances as $previous_balance)
                <tr class="">
                    <td class="align-middle w150 left">{{ $previous_balance['period_code'] }}</td>
                    <td class="align-middle left">{{ $previous_balance['period_name'] }}</td>
                    <td class="align-middle w150 right">{{ number_format($previous_balance['debit'],2) }}</td>
                    <td class="align-middle w150 right">{{ number_format($previous_balance['credit'],2) }}</td>
                    <td class="align-middle w150 right">{{ number_format($previous_balance['balance'],2) }}</td>
                    @if (isset($forwardable) && $forwardable == true)
                        <td class="align-middle right">
                            <button type="button" id="{{ $previous_balance['period_id'] }}" class="forward_balance btn btn-sm btn-primary btn-icon-split m-1">
                                <span class="icon text-white-50">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="text">Forward Balance</span>
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@endif