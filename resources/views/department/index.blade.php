@extends('layout')
@section('title') {{ 'Departments List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Departments</h1>
        <p class="mb-4">List of all departments in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">departments Table</h6> --}}
                <a href="{{ route('adddepartment') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new department</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="departmentTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Head</th>
                                <th>Edit</th>
                                <th>Delete</th>
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
                            @if ($departments)
                                @unless (count($departments) == 0)
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $department->code }}</td>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->headName }}</td>
                                            <td class="">
                                                <a href="{{ route('editdepartment', ['department' => $department->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Edit</span>
                                                </a>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('deletedepartment', ['department' => $department->id ]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-icon-split">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </span>
                                                        <span class="text">Delete</span>
                                                    </button>
                                                </form>
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