<!-- Begin Page Content -->
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
                                                        }elseif ($evaluation[$evaluation_key]['grade_info']['inprogress'] === 1) {
                                                            $class = 'black';
                                                        }
                                                    @endphp
                                                    <td>
                                                        {{-- {{ $subject->id }} --}}
                                                        <label
                                                            id="{{ $subject->id }}" 
                                                            class="mb-0 font-weight-bold text-{{ $class }}"
                                                            data-origin="{{ $evaluation[$evaluation_key]['grade_info']['origin'] }}"
                                                            data-grade_id="{{ $evaluation[$evaluation_key]['grade_info']['grade_id'] }}"
                                                        >{{ $subject->subjectinfo->code }}</label>
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
                                                <td class="mid">{{ $subject->quota }}</td>
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