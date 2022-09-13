@extends('layout')
@section('title') {{ 'Programs List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Programs</h1>
        <p class="mb-4">List of all programs in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">programs Table</h6> --}}
                <a href="{{ route('programs.create') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new program</span>
                </a>
                <a href="{{ route('subjects.create') }}" class="btn btn-danger btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-print"></i>
                    </span>
                    <span class="text">Print PDF</span>
                </a>
                <a href="{{ route('subjects.create') }}" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-download"></i>
                    </span>
                    <span class="text">Download Excel</span>
                </a>
                <a href="{{ route('programs.import') }}" class="btn btn-secondary btn-icon-split" id="upload_excel" data-field="subjects">
                    <span class="icon text-white-50">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="text">Upload Excel</span>
                </a>
                <div>
                    <form method="POST" action="" id="filter_form">
                        @csrf
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="department" class="m-0 font-weight-bold text-primary">Keyword</label>
                                        <input type="text" name="middle_name" placeholder="Type keyword to search..." class="form-control" id="keyword">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="college" class="m-0 font-weight-bold text-primary">College</label>
                                        @include('partials.colleges.dropdown', ['fieldname' => 'college_id', 'fieldid' => 'college_id', 'fieldclass' => 'dropdownfilter'])
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="educational_level" class="m-0 font-weight-bold text-primary">Level</label>
                                        @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id', 'fieldclass' => 'dropdownfilter'])
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="designation" class="m-0 font-weight-bold text-primary">Status</label>
                                        <select name="designation" class="form-control dropdownfilter" id="status">
                                            <option value="">- select status -</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="programTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Years</th>
                                <th>Level</th>
                                <th>College</th>
                                <th>Head</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </tfoot> --}}
                        <tbody>
                            @if ($programs)
                                @unless (count($programs) == 0)
                                    @foreach ($programs as $program)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $program->code }}</td>
                                            <td>{{ $program->name }}</td>
                                            <td>{{ $program->years }}</td>
                                            <td>{{ $program->level->level }}</td>
                                            <td>{{ $program->collegeinfo->code }}</td>
                                            <td>{{ $program->headName }}</td>
                                            <td>{{ $program->active }}</td>
                                            <td class="">
                                                <a href="{{ route('programs.edit', ['program' => $program->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Edit</span>
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