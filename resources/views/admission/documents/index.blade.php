@extends('layout')
@section('title') {{ 'Admission Documents' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Admission Documents</h1>
        <p class="mb-4">List and management of documents required for admission.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary mb-0">Admission Documents Management</h6>
                    </div>
                    <div class="col-md-6 right">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                <form method="POST" action="" id="form_add_admission_document" class="form_document">
                                    @csrf
                                    <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="educational_level" class="m-0 font-weight-bold text-primary">* Level</label>
                                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id', 'fieldclass' => 'clearable'])
                                                <div id="error_educational_level_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="program_id" class="m-0 font-weight-bold text-primary">Program</label>
                                                <select name="program_id" class="form-control clearable" id="program_id">
                                                    <option value="">- select program -</option>
                                                    @if ($programs)
                                                        @foreach ($programs as $program)
                                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="error_program_id" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 align-items-end">
                                            <div class="form-group mid">
                                                <label for="display" class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="display">
                                                    <input type="checkbox" class="checkbox" name="display" value="1" id="display"> Display</label>
                                                <label for="is_required" class="m-0 font-weight-bold text-primary checkbox-inline pr-3">
                                                    <input type="hidden" value="0" name="is_required">
                                                    <input type="checkbox" class="checkbox" name="is_required" value="1" id="is_required"> Is Required</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="classification" class="m-0 font-weight-bold text-primary">Classifcation</label>
                                                <select name="classification" class="form-control clearable" id="classification">
                                                    <option value="">- select classification -</option>
                                                    @foreach (\App\Models\Student::STUDENT_CLASSIFICATION as $key => $classfication)
                                                        <option value="{{ $key }}">{{ $classfication }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="error_classification" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="description" class="m-0 font-weight-bold text-primary">* Document Description</label>
                                                <input type="text" name="description" placeholder="" class="form-control text-uppercase clearable" id="description">
                                                <div id="error_description" class="errors"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group" id="button_group">
                                                <button type="submit" id="save_setup_fee" class="btn btn-sm btn-success btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-save"></i>
                                                    </span>
                                                    <span class="text">Save</span>
                                                </button>
                                                <button type="button" id="edit" class="btn btn-sm btn-primary btn-icon-split actions mb-2" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Edit</span>
                                                </button>
                                                <button type="button" id="delete_selected" class="btn btn-sm btn-danger btn-icon-split actions mb-2" disabled>
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    <span class="text">Delete</span>
                                                </button>
                                                <button type="button" id="cancel" class="btn btn-sm btn-danger btn-icon-split mb-2">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    <span class="text">Cancel</span>
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
                        <h6 class="m-0 font-weight-bold text-primary">List of Documents</h6>
                    </div>
                    <div class="card-body">
                        <div id="return_admission_documents">
                            @include('admission.documents.return_documents')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection