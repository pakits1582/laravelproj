@extends('layout')
@section('title') {{ 'Scholarship Discount Grant' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Scholarship Discount Grant</h1>
        <p class="mb-4">Grant enrolled student scholarship or discount.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Scholarship/Discount Grant <span id="period_name">{{ session('periodname') }}</span></h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action=""  role="form" id="form_scholarshipdiscountgrant">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Assessment No.</label>
                                                <input type="text" name="assessment_id" id="assessment_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">* Enrollment No.</label>
                                                <input type="text" name="enrollment_id" id="enrollment_id" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Student</label>
                                                <select name="student_id" class="form-control select" id="student">
                                                    <option value="">- search student -</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Level</label>
                                                <input type="text" id="educational_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">College</label>
                                                <input type="text" id="college" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Program</label>
                                                <input type="text" id="program" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Period</label>
                                                <select name="period_id" class="form-control" id="period" required>
                                                    @if ($periods)
                                                        @foreach ($periods as $period)
                                                            <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_period_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">Curriculum</label>
                                                <input type="text" id="curriculum" class="form-control text-uppercase clearable" readonly value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Year Level</label>
                                                <input type="text" id="year_level" class="form-control text-uppercase clearable" readonly value="" placeholder="">

                                            </div>                                 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="code"  class="m-0 font-weight-bold text-primary">Section</label>
                                                <input type="text" name="" id="section" class="form-control text-uppercase clearable" readonly value="" placeholder="">

                                            </div>                                 
                                        </div>
                                    </div>
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="term" class="m-0 font-weight-bold text-primary">* Grant</label>
                                                <select name="scholarshipdiscount_id" class="form-control" id="scholarshipdiscount" required>
                                                    <option value="">- select grant -</option>
                                                    @if ($scholarshipdiscounts)
                                                        @foreach ($scholarshipdiscounts as $scholarshipdiscount)
                                                            <option value="{{ $scholarshipdiscount->id }}">{{ $scholarshipdiscount->description }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_scholarshipdiscount_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 right">
                                            <div class="form-group right" id="button_group">
                                                <button type="submit" id="save_grant" class="btn btn-success btn-icon-split actions" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-save"></i>
                                                    </span>
                                                    <span class="text">Save Scholarship/Discount Grant</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                          <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List of Grants</h6>
                    </div>
                    <div class="card-body" id="">
                        <div class="table-responsive-sm">
                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                <thead class="">
                                    <tr>
                                        <th class="w170">Code</th>
                                        <th class="">Description</th>
                                        <th class="w120">Tuition</th>
                                        <th class="w120">Miscellaneous</th>
                                        <th class="w120">Other Misc.</th>
                                        <th class="w120">Laboratory</th>
                                        <th class="w120">Total Assessment</th>
                                        <th class="w120">Total Deduction</th>
                                        <th class="w50">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="return_scholarshipdiscount_grant">
                                    <tr><td class="mid" colspan="9">No records to be displayed!</td></tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection