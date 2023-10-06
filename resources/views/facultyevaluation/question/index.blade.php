@extends('layout')
@section('title') {{ 'Survey Questions' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Survey Questions</h1>
        <p class="mb-4">Manage faculty evaluation survey questions.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h6 class="font-weight-bold text-primary mb-0">Survey Questions Management</h6>
                    </div>
                    <div class="col-md-5 right">
                        <a href="{{ route('facultyevaluations.index') }}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                            <span class="text">Faculty Evaluations</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="" >
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id', 'fieldclass' => '', 'value' => 1])
                            </div>
                        </div>
                        <div class="col-md-9  right">
                            <div class="form-group">
                                <a href="#" id="add_question" class="btn btn-sm btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">Add Question</span>
                                </a>
                                <a href="#" id="delete_all_questions" class="btn btn-sm btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete All Questions</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="return_questions">
                    @include('facultyevaluation.question.return_surveyquestions')
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection