{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Statement of Accounts' }} @endsection
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Statement of Accounts</h1>
    <p class="mb-4">View current semester accounts and previous balance or refund.</p>

    @include('partials.student.information')
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Statement of Account</h6>
        </div>
        <div class="card-body">
            <div id="">
                @include('studentledger.statementofaccount')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Previous Balance or Refund</h6>
                </div>
                <div class="card-body">
                    <div id="">
                        @include('studentledger.previousbalance')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Schedule</h6>
                </div>
                <div class="card-body">
                    @if (isset($enrollment) && $enrollment->validated != 0 && $payment_schedule['assessment_exam'])
                        @include('studentledger.payment_schedule')               
                    @else
                        <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Previous Statement of Accounts</h6>
        </div>
        <div class="card-body">
            <div id="">
                @include('studentledger.student.previous_soas')
            </div>
        </div>
    </div>
</div>
@endsection