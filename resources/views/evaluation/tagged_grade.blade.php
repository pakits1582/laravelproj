 <!-- Logout Modal-->
 <div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Evaluation Tag Grade</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tag Grade to Curriculum Subject</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="year" class="m-0 font-weight-bold text-primary">Code</label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-0 font-weight-bold text-black">{{ $curriculum_subject->subjectinfo->code }}</div>
                            </div>
                            <div class="col-md-1">
                                <label for="year" class="m-0 font-weight-bold text-primary">Quota</label>
                            </div>
                            <div class="col-md-1 mid">
                                <div class="mb-0 font-weight-bold text-black">{{ $curriculum_subject->quota }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label for="year" class="m-0 font-weight-bold text-primary">Description</label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-0 font-weight-bold text-black">{{ $curriculum_subject->subjectinfo->name }}</div>
                            </div>
                            <div class="col-md-1">
                                <label for="year" class="m-0 font-weight-bold text-primary">Units</label>
                            </div>
                            <div class="col-md-1 mid">
                                <div class="mb-0 font-weight-bold text-black">{{ $curriculum_subject->subjectinfo->units }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-0">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Student's Passed Grades</h6>
                    </div>
                    <div class="card-body">
                        <p class="font-italic text-info">Note: (*) Denotes grade is from external grade.</p>
                        <div class="px-2 bg-success text-black font-italic">Green colored row denotes that grade had already been tagged.</div>
                        <div class="px-2 bg-warning text-black font-italic">Yellow colored row denotes that grade is currently tagged to the selected subject.</div>
                        <div class="px-2 bg-primary text-black font-italic">Blue colored row denotes that grade is currently selected.</div>
                        <form action="{{ route('evaluations.store') }}" method="post" id="form_tag_grade">
                            <div class="col-xs-8 col-xs-offset-2 well">
                                    <table class="table table-scroll table-striped table-bordered" style="">
                                        <thead>
                                            <tr>
                                                <th class="w50">#</th>
                                                <th class="w120">Code</th>
                                                <th>Name</th>
                                                <th class="w60">Units</th>
                                                <th class="w75">Grade</th>
                                                <th class="w60">C.G.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($allgrades) > 0)
                                                @foreach ($allgrades as $grade)
                                                    @php
                                                        $checked = '';
                                                        $isselected = false;
                                                        $istaggedcolor = '';
                                                        $istagged = 0;

                                                        if(Helpers::is_column_in_array($grade['id'], 'grade_id' ,$all_tagged_grades->toArray()))
                                                        {
                                                            $istagged = 1;
                                                            foreach ($all_tagged_grades as $key => $tagged_grade) {
                                                                $origin = ($tagged_grade->origin === 0) ? 'internal' : 'external';

                                                                if($grade['id'] === $tagged_grade->grade_id && $grade['origin'] === $origin)
                                                                {
                                                                    $checked = 'checked';
                                                                    $isselected = true;
                                                                }else{
                                                                    $istaggedcolor = 'success';
                                                                }
                                                            }
                                                        }
                                                    @endphp        
                                                    <tr class="label bg-{{ ($isselected) ? 'warning' : $istaggedcolor }}" id="remove_{{ $grade['id'] }}_{{ $grade['origin'] }}" >
                                                        <td class="w50">
                                                            <input type="checkbox" name="cboxtag[]" value="{{ $grade['id'] }}" class="cboxtag nomargin" id="{{ $grade['id'] }}" data-origin="{{ $grade['origin'] }}"
                                                                {{ $checked }} data-istagged="{{ $istagged }}"
                                                            />
                                                            <input type="hidden" name="origin_{{ $grade['id'] }}" value="{{ $grade['origin'] }}"  />
                                                        </td>
                                                        <td class="w120 font-weight-bold">{{ $grade['subject_code'] }}</td>
                                                        <td>{{ $grade['subject_name'] }}</td>
                                                        <td class="w60 mid">{{ $grade['units'] }}</td>
                                                        <td class="w75 mid">{{ $grade['grade'] }}</td>
                                                        <td class="w60 mid">{{ $grade['completion_grade'] }}</td>
                                                    </tr>
                                                @endforeach
                                                
                                            @else
                                                <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                            
                            </div>
                            <input type="hidden" name="curriculum_subject_id" value="{{ $curriculum_subject->id }}"  />
                            <input type="hidden" name="student_id" value="{{ $student->id }}"  />
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a> --}}
         </div>
     </div>
 </div>
</div>