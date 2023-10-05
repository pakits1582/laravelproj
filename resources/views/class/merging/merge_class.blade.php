 <!-- Logout Modal-->
 <div class="modal fade" id="merge_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Merge Class Subjects</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $class['sectioninfo']['code'] }} - ({{ $class['code'] }}) {{ $class['curriculumsubject']['subjectinfo']['code'] }} - {{ $class['curriculumsubject']['subjectinfo']['name'] }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3 mid">
                                <label for="search_classtomerge" class="m-0 font-weight-bold text-primary">Search Class to Merge</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control text-uppercase" id="search_classtomerge" value="">
                            </div>
                        </div>
                        <p class="font-italic text-info">Note: You can search multiple subject code by separating it by comma (,) code 1, code 2..</p>
                        <form action="{{ route('classes.savemerge') }}" method="post" id="form_merge_class">
                            <div id="return_search_classtomerge">
                                @include('class.merging.return_search_code_results')
                            </div>
                            <input type="hidden" name="class_id" id="class_id" value="{{ $class['id'] }}"  />
                            <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm mt-3">Merge Classes</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=" py-0 px-1"> 
                <div class="card shadow mb-0">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Merged Class Subjects</h6>
                    </div>
                    <div class="card-body">
                        <div id="return_merged_classes">
                            @include('class.merging.merged_classes')
                        </div>
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