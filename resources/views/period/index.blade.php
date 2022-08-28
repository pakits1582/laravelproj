@extends('layout')
@section('title') {{ 'Period List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Period</h1>
        <p class="mb-4">List of all periods in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">instructor Table</h6> --}}
                <a href="{{ route('addperiod') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new period</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="periodTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Term</th>
                                <th>Year</th>
                                <th>Enroll Start</th>
                                <th>Add Drop Start</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($periods)
                                @unless (count($periods) == 0)
                                    @foreach ($periods as $period)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $period->code }}</td>
                                            <td>{{ $period->name }}</td>
                                            <td>{{ $period->term }}</td>
                                            <td>{{ $period->year }}</td>
                                            <td>{{ $period->enroll_start  }}</td>
                                            <td>{{ $period->adddrop_start  }}</td>
                                            <td>{{ $period->priority_lvl }}</td>
                                            <td class="cente">
                                                <a href="{{ route('editperiod', ['period' => $period->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endunless
                            @else
                                <tr><td colspan="6">No records to be displayed!</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection