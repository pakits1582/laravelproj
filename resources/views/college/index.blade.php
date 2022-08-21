@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Colleges</h1>
        <p class="mb-4">List of all colleges in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">colleges Table</h6> --}}
                <a href="{{ route('addcollege') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new college</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="collegeTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Dean</th>
                                <th>Actions</th>
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
                            @if ($colleges)
                                @unless (count($colleges) == 0)
                                    @foreach ($colleges as $college)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $college->code }}</td>
                                            <td>{{ $college->name }}</td>
                                            <td>{{ $college->dean }}</td>
                                            <td class="cente">
                                                <a href="{{ route('editcollege', ['college' => $college->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('editcollege', ['college' => $college->id ]) }}" class="btn btn-danger btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </span>
                                                </a>
                                                {{-- <form method="POST" action="{{ route('deletecollege', ['college' => $college->id ]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-icon-split">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </span>
                                                        <span class="text">Delete</span>
                                                    </button>
                                                </form> --}}
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