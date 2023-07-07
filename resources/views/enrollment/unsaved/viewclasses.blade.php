<div class="modal fade" id="view_classes_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Enrolled/Reserved Classes</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-1"> 
                <table id="scrollable_table_transfer" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="w50">Code</th>
                            <th class="w150">Section</th>
                            <th class="">Subject</th>
                            <th class="">Description</th>
                            <th class="w50">Units</th>
                            <th class="">Schedule</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($enrollment->enrolled_classes))
                            @foreach ($enrollment->enrolled_classes as $enrolled_class)
                                <tr>
                                    <td class="">{{ $loop->iteration }}</td>
                                    <td class="">{{ $enrolled_class->class->code }}</td>
                                    <td class="">{{ $enrolled_class->class->sectioninfo->code }}</td>
                                    <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->code }}</td>
                                    <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->name }}</td>
                                    <td class="mid">{{ $enrolled_class->class->units }}</td>
                                    <td class="">{{ $enrolled_class->class->schedule->schedule }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="">&nbsp;</td>
                                <td class="">&nbsp;</td>
                                <td class="">&nbsp;</td>
                                <td class="">&nbsp;</td>
                                <td class="">&nbsp;</td>
                                <td class="">&nbsp;</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
         </div>
     </div>
 </div>
</div>