@extends('layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Users</h1>
        <p class="mb-4">List of all users in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">users Table</h6> --}}
                <a href="{{ route('adduser') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new user</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Number</th>
                                <th>Name</th>
                                <th>Status</th>
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
                            @if ($users)
                                @unless (count($users) == 0)
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->idno }}</td>
                                            <td>{{ $user->info->name }}</td>
                                            <td>{{ ($user->is_active == 1) ? 'Active' : 'Inactive'  }}</td>
                                            <td class="cente">
                                                <a href="{{ route('edituser', ['user' => $user->id ]) }}" class="btn btn-primary btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-edit"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('edituser', ['user' => $user->id ]) }}" class="btn btn-success btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-power-off"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('edituser', ['user' => $user->id ]) }}" class="btn btn-info btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-undo"></i>
                                                    </span>
                                                </a>
                                                <a href="{{ route('edituser', ['user' => $user->id ]) }}" class="btn btn-danger btn-icon-split">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </span>
                                                </a>
                                                {{-- <form method="POST" action="{{ route('deleteuser', ['user' => $user->id ]) }}">
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