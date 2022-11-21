{{-- {{ dd($evaluation) }} --}}
@extends('layout')
@section('title') {{ 'Evaluate Student' }} @endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-2">
            <h1 class="h3 text-800 text-primary m-0">Student's Evaluation</h1>
        </div> 
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">ID No.</label>
                </div>
                <div class="col-md-4">
                    {{ $student->user->idno }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Program</label>
                </div>
                <div class="col-md-6">
                    ({{ $student->program->code }}) {{ $student->program->name }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Name</label>
                </div>
                <div class="col-md-4">
                    {{ $student->name }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Year</label>
                </div>
                <div class="col-md-2">
                    {{ Helpers::yearLevel($student->year_level) }}
                </div>
                <div class="col-md-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Curriculum</label>
                </div>
                <div class="col-md-3">
                    {{ $student->curriculum->curriculum }}
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-2 mid">
            <h1 class="h3 text-800 text-primary m-0">Curriculum {{ $curriculum->curriculum }}</h1>
            <input type="hidden" name="curriculum_id" value="{{ $curriculum->id }}" id="curriculum_id" />
        </div> 
        <div class="card-body">
            @if ($program)
                @for ($x=1; $x <= $program->years; $x++)
                    <h1 class="h3 text-800 text-primary mid">{{ Helpers::yearLevel($x) }}</h1>
                    <div class="row">
                        @foreach ($curriculum_subjects as $yearlevel => $curriculum_subject)
                        @if ($yearlevel === $x)
                        
                            @foreach ($curriculum_subject as $term => $subjects)
                            <div class="col-md-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">{{ $term }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive-sm">
                                            <table class="table table-sm table-striped table-bordered" style="font-size: 12px;">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th class="w40 mid">Grade</th>
                                                        <th class="w40 mid">C.G.</th>
                                                        <th class="w100">Code</th>
                                                        <th>Descriptive Title</th>
                                                        <th class="w20">Quota</th>
                                                        <th class="w20">Units</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-black">
                                                    @php
                                                        $totalunits = 0;
                                                    @endphp
                                                    @foreach ($subjects as $subject)
                                                        @php
                                                            $evaluation_key = Helpers::is_column_in_array($subject->id, 'id', $evaluation);
                                                        @endphp
                                                        <tr>
                                                            <td class="mid font-weight-bold">{{ $evaluation[$evaluation_key]['grade_info']['finalgrade'] }}</td>
                                                            <td class="mid font-weight-bold">{{ $evaluation[$evaluation_key]['grade_info']['completion_grade'] }}</td>
                                                            @if ($evaluation[$evaluation_key]['grade_info']['manage'] === true)
                                                                @php
                                                                    $class = 'danger';
                                                                    if($evaluation[$evaluation_key]['grade_info']['finalgrade'] !== '')
                                                                    {
                                                                        $class = 'primary';
                                                                    }

                                                                @endphp
                                                                <td>
                                                                    <a href="#" id="{{ $subject->id }}" class="font-weight-bold text-{{ $class }} manage_curriculum_subject">{{ $subject->subjectinfo->code }}</a>
                                                                </td>
                                                            @else
                                                                <td class="font-weight-bold text-success">{{ $subject->subjectinfo->code }}</td>
                                                            @endif
                                                            
                                                            <td>
                                                                {{ $subject->subjectinfo->name }}
                                                                @if (count($subject->prerequisites) > 0)
                                                                    <span class="font-weight-bold text-dark">
                                                                        (
                                                                        @foreach ($subject->prerequisites as $prerequisite)
                                                                            {{ $loop->first ? '' : ', ' }}
                                                                            {{ $prerequisite->curriculumsubject->subjectinfo->code }}
                                                                        @endforeach
                                                                        )
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $subject->quota }}</td>
                                                            <td class="mid">{{ $subject->subjectinfo->units }}</td>
                                                        </tr>
                                                        @php
                                                            $totalunits += $subject->subjectinfo->units
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5" class="text-right  font-weight-bold text-primary">Total Units</td>
                                                        <td class="mid  font-weight-bold text-primary">{{ $totalunits }}</td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        @endforeach
                    </div>
                @endfor
            @endif
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection