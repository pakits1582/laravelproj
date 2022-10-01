{{-- {{ dd($curriculum_subjects) }} --}}
<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h1 class="h3 text-800 text-primary">Curriculum {{ $curriculum->curriculum }}</h1>
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
                                            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th class="w20 mid"><i class="fas fa-fw fa-cog"></i></th>
                                                        <th class="w100">Course No.</th>
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
                                                        <tr>
                                                            <td>
                                                                <a href="#" class="btn btn-danger btn-circle btn-sm delete_item" id="{{ $subject->id }}" data-action="curriculum_subject" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </td>
                                                            <td><a href="#" id="{{ $subject->id }}" class="font-weight-bold text-primary manage_curriculum_subject">{{ $subject->subjectinfo->code }}</a></td>
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
                                                        <td colspan="4" class="text-right  font-weight-bold text-primary">Total Units</td>
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