@extends('layout')
@section('title') {{ 'Subjects List' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Subjects</h1>
        <p class="mb-4">List of all subjects in the database</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                {{-- <h6 class="m-0 font-weight-bold text-primary">Subjects Table</h6> --}}
                <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-square"></i>
                    </span>
                    <span class="text">Add new subject</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="subjectTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Units</th>
                                <th>Tuition</th>
                                <th>Load</th>
                                <th>Lec</th>
                                <th>Lab</th>
                                <th>Hours</th>
                                <th>isProf</th>
                                <th>isLab</th>
                                <th>Level</th>
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
                            @if ($subjects)
                                @unless (count($subjects) == 0)
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subject->code }}</td>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->units }}</td>
                                            <td>{{ $subject->tfunits }}</td>
                                            <td>{{ $subject->loadunits }}</td>
                                            <td>{{ $subject->lecunits }}</td>
                                            <td>{{ $subject->labunits }}</td>
                                            <td>{{ $subject->hours }}</td>
                                            <td>{{ ($subject->professional == 1) ? 'YES' : 'NO' }}</td>
                                            <td>{{ ($subject->laboratory == 1) ? 'YES' : 'NO' }}</td>
                                            <td>{{ $subject->educlevel->code }}</td>
                                            <td class="">
                                                <a href="{{ route('subjects.edit', ['subject' => $subject->id ]) }}" class="btn btn-primary btn-icon-split">
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