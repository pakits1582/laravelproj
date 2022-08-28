@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Instructors</h1>
        <p class="mb-4">List of all instructors in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">instructor Table</h6> --}}
                <a href="{{ route('addinstructor') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new instructor</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="instructorTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Number</th>
                                <th>Name</th>
                                <th>College</th>
                                <th>Educ Level</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($instructors)
                                @unless (count($instructors) == 0)
                                    @foreach ($instructors as $instructor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $instructor->user->idno }}</td>
                                            <td>{{ $instructor->fullname }}</td>
                                            <td>{{ $instructor->collegeinfo->code }}</td>
                                            <td>{{ $instructor->educlevel->level }}</td>
                                            <td>{{ $instructor->deptcode  }}</td>
                                            <td>{{ Helpers::getDesignation($instructor->designation) }}</td>
                                            <td>{{ ($instructor->user->is_active == 1) ? 'Active' : 'Inactive'  }}</td>
                                            <td class="cente">
                                                <a href="{{ route('editinstructor', ['instructor' => $instructor->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('editinstructor', ['instructor' => $instructor->id ]) }}" class="btn btn-success btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-power-off"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('editinstructor', ['instructor' => $instructor->id ]) }}" class="btn btn-info btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-undo"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('editinstructor', ['instructor' => $instructor->id ]) }}" class="btn btn-danger btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash-alt"></i>
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