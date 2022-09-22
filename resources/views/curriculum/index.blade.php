@extends('layout')
@section('title') {{ 'Curriculum Management' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Curriculum Management</h1>
        <p class="mb-4">List of all programs in the database</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary">All programs under Deanship/Headship</h1>
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
                                <th>Head</th>
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
                                            <td>{{ $program->headName }}</td>
                                            <td class="mid">
                                                <a href="{{ route('curriculum.manage', ['program' => $program->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                    <span class="text">Manage</span>
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