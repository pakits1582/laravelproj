 <!-- Logout Modal-->
 <div class="modal fade" id="enrolledinsection_modal" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 90% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Students Enrolled in Section</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-1">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $section->programinfo->name }} (SECTION: {{ $section->name }})</h6>
                    </div>
                    <div class="card-body">
                        <table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
                            <thead>
                                <tr>
                                    <th class="w100">Enroll No.</th>
                                    <th class="w100">ID Number</th>
                                    <th class="w300">Name</th>
                                    <th class="w150">Program & Yr.</th>
                                    <th class="w50">Units</th>
                                    <th class="w120">Enrolled By</th>
                                    <th class="w200">Enroll Date</th>
                                    <th class="w120">Enroll Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($section->enrollments) > 0)
                                    @foreach ($section->enrollments as $enrollment)
                                        @php
                                            if ($enrollment->acctok == 1 && $enrollment->assessed == 0 && $enrollment->validated == 0) {
                                                $enrollstatus = 'Saved';
                                            } else if ($enrollment->acctok == 1 && $enrollment->assessed == 1 && $enrollment->validated == 0) {
                                                $enrollstatus = 'Assessed';
                                            } else if ($enrollment->acctok == 1 && $enrollment->assessed == 1 && $enrollment->validated == 1) {
                                                $enrollstatus = 'Validated';
                                            } else {
                                                $enrollstatus = 'Unsaved';
                                            }
                                        @endphp

                                        <tr>
                                            <td class="w100">{{ $enrollment->id }}</td>
                                            <td class="w100 mid">{{ $enrollment->student->user->idno }}</td>
                                            <td class="w300">{{ $enrollment->student->name }}</td>
                                            <td class="w150">{{ $enrollment->program->code }} - {{ $enrollment->year_level }}</td>
                                            <td class="w50 mid">{{ $enrollment->enrolled_units }}</td>
                                            <td class="w120"></td>
                                            <td class="w200">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('F d, Y h:i:s') }}</td>
                                            <td class="w120">{{ $enrollstatus }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th class="w100">&nbsp;</th>
                                        <th class="w100">&nbsp;</th>
                                        <th class="w300">&nbsp;</th>
                                        <th class="w200">&nbsp;</th>
                                        <th class="w50">&nbsp;</th>
                                        <th class="w120">&nbsp;</th>
                                        <th class="w200">&nbsp;</th>
                                        <th class="w120">&nbsp;</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>                        
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