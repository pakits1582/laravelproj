@extends('layout')
@section('title') {{ 'Masterlist' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Masterlist</h1>
        <p class="mb-4">List of all students enrolled in the period.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Masterlist <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection