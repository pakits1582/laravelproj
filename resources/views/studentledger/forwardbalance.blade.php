 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Forward Balance</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            {{-- @dump($soa_to_forward) --}}
            <div class="container py-0 px-0">       
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                            <form method="POST" action=""  role="form" id="form_forwardbalance">
                                @csrf
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_name" class="m-0 font-weight-bold text-primary">Student Name</label>
                                        </div>
                                        <div class="col-md-9">
                                            <h6 class="m-0 font-weight-bold text-black">({{ $student->user->idno }}) {{ $student->name }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_type" class="m-0 font-weight-bold text-primary">Period to Forward</label>
                                        </div>
                                        <div class="col-md-9">
                                            <h6 class="m-0 font-weight-bold text-black">{{ $soa_to_forward[0]['period_name'] }}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="fee_amount" class="m-0 font-weight-bold text-primary">Balance to Forward</label>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="m-0 font-weight-bold text-black">{{ number_format($soa_to_forward[0]['balance'], 2) }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <p class="font-italic text-info">Note: Please select period/term where balance will be forwarded.</p>
                                <h6 class="m-2 font-weight-bold text-primary">Student's Enrollment Ledger</h6>
                                <div class="table-responsive" id="table_data">
                                    <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                        <thead class="">
                                            <tr>
                                                <th class="w30"></th>
                                                <th class="w100">Code</th>
                                                <th class="">Period Name</th>
                                                <th class="w100">Payables</th>
                                                <th class="w100">Payments</th>
                                                <th class="w100">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black" id="">
                                            @if (count($studentledgers) > 0)
                                                @foreach ($studentledgers as $studentledger)
                                                    <tr>
                                                        <td class="mid">
                                                            <input type="checkbox" name="period_to"  class="checks" value="{{ $studentledger['period_id'] }}" />
                                                        </td>
                                                        <td class="mid">{{ $studentledger['period_code'] }}</td>
                                                        <td>{{ $studentledger['period_name'] }}</td>
                                                        <td class="right">{{ number_format($studentledger['debit'],2) }}</td>
                                                        <td class="right">{{ number_format($studentledger['credit'],2) }}</td>
                                                        <td class="right">{{ number_format($studentledger['balance'],2) }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="6" class="mid">No records to be displayed!</td></tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="mid">
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="period_from" value="{{ $soa_to_forward[0]['period_id'] }}">
                                                    <input type="hidden" name="balance" value="{{ $soa_to_forward[0]['balance'] }}">
                                                    <button type="submit" id="" class="btn btn-primary btn-icon-split m-1">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </span>
                                                        <span class="text">Forward Balance</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </form>
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