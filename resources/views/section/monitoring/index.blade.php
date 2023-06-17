@extends('layout')
@section('title') {{ 'Section Monitoring' }} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Section Monitoring</h1>
        <p class="mb-4">List of all sections offered of current term and their enrollment size.</p>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 text-800 text-primary mb-0">Section Monitoring</h1>
            </div>
            <div class="card-body">
                <form method="POST" id="form_filterslotmonitoring" action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="educational_level" class="m-0 font-weight-bold text-primary">Programs</label>
                                <select name="room_id" class="form-control filter_item" id="room_id">
                                    <option value="">- select program -</option>
                                    {{-- @if ($rooms)
                                        @foreach ($rooms as $room)
                                            @if ($room->room_id != NULL)
                                                <option value="{{ $room->room_id }}">{{ $room->room_code }}</option>
                                            @endif
                                        @endforeach
                                    @endif --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </div>
                </form>
                <div id="return_slotmonitoring">
                    @include('section.monitoring.return_sectionmonitoring')
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection