@extends('layout')
@section('title') {{ 'Class List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Class List</h1>
        <p class="mb-4">List of all students enrolled in class.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Class List <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filterclasslist" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="period_id" class="m-0 font-weight-bold text-primary">Period</label>
                                <select name="period_id" class="form-control filter_item" id="period_id">
                                    @if ($periods)
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Criteria</label>
                                <select name="criteria" class="form-control filter_item" id="criteria">
                                    <option value="code">CODE</option>
                                    <option value="instructor">INSTRUCTOR</option>
                                    <option value="subject">SUBJECT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="college" class="m-0 font-weight-bold text-primary">Keyword</label>
                                <input type="text" name="keyword" placeholder="Type keyword to search..." class="form-control text-uppercase" id="search_keyword">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Dissolved</label>
                                <div class="legend dissolved"></div>   
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">Tutorial</label>
                                <div class="legend tutorial"></div>   
                            </div>
                        </div>
                    </div>
                </form>
                <div id="return_classlist">
                    @include('classlist.return_classlist')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Student List and Subject Information</h6>
                    </div>
                    <div class="card-body">
                        <div id="return_class_info">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection