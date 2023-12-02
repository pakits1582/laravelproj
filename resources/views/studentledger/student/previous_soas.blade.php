<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payables and Payments Trail</h6>
            </div>
            <div class="card-body" id="return_studentledger">
                @if (count($previous_soas) > 0)
                    @foreach ($previous_soas as $soa)
                        <h6 class="font-weight-bold text-primary">{{ $soa['period_code'] }} - {{ $soa['period_name'] }}</h6>
                        <div class="table-responsive" id="table_data">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w200">Date</th>
                                        <th class="w150">Doc No.</th>
                                        <th class="">Particular</th>
                                        <th class="w100">Debit</th>
                                        <th class="w100">Credit</th>
                                        <th class="w100">Balance</th>
                                        <th class="w150">User</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="">
                                    @php
                                        $assessmenttotal = 0;
                                        $debittotal = 0;
                                        $credittotal = 0;
                                        $balance = 0;
                                    @endphp 
                                    @foreach ($soa['soa'] as $ledger)
                                        @php
                                            $debit = 0;
                                            $credit = 0;

                                            if($ledger['type'] == 'A')
                                            {
                                                $assessmenttotal = $ledger['amount'];
                                                $balance = $assessmenttotal;
                                                $debit = $ledger['amount'];
                                            }else{
                                                if($ledger['debitcredit'] == 'credit')
                                                {
                                                    if($ledger['type'] == 'R' && $ledger['cancelled'] == 1)
                                                    {
                                                        $credit = str_replace('-', "", $ledger['amount']);
                                                    }else{
                                                        $credit = str_replace('-', "", $ledger['amount']);

                                                        $balance = bcsub($balance, $credit,2);   
                                                    }
                                                }else{
                                                    $debit = $ledger['amount'];
                                                    $balance += $debit;
                                                }
                                            }
                                            
                                            $user = ($ledger['user']) ? $ledger['user'] : '';
                                            $acronym = '';
                                            if($user != ''){
                                                $name = explode(" ", $user);
                                                $acronym = (count($name) == 1) ? $name[0] : current($name)[0].'. '.end($name);
                                            }

                                            $particular = ($ledger['type'] == 'R' && $ledger['cancelled'] == 1) ? $ledger['particular'].' - CANCELLED ('.$ledger['cancel_remark'].')' : $ledger['particular'];
                                        @endphp 
                                        <tr>
                                            <td class="w200">{{ \Carbon\Carbon::parse($ledger['created_at'])->format('F d, Y') }}</td>
                                            <td class="w150">{{ $ledger['code'] }}</td>
                                            <td class="">{{ $particular }}</td>
                                            <td class="w100">{{ number_format($debit,2) }}</td>
                                            <td class="w100">{{ number_format(str_replace('-', "", $credit),2) }}</td>
                                            <td class="w100">{{ number_format($balance,2) }}</td>
                                            <td class="w150">{{ $acronym }}</td>
                                            @php
                                                $debittotal += $debit;
                                                $credittotal += ($ledger['type'] == 'R' && $ledger['cancelled'] == 1) ? 0 : $credit; 
                                                //$credittotal += $credit; 
                                            @endphp
                                        </tr>
                                    @endforeach
                                    <tr>    
                                        <td colspan="3" class="right font-weight-bold text-primary">TOTAL:</td>
                                        <td class="right font-weight-bold text-primary">{{ number_format(str_replace('-', "", $debittotal),2) }}</td>
                                        <td class="right font-weight-bold text-primary">{{ number_format(str_replace('-', "", $credittotal),2) }}</td>
                                        <td class="right font-weight-bold text-primary">{{ number_format(bcsub($debittotal,str_replace('-', "", $credittotal),2),2) }}</td>
                                        <td class="right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

