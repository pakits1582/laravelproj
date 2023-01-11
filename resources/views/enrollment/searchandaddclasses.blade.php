 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true" data-keyboard="false" data-backdrop="static">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Search and Add Classes</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-3"> 
                <div class="card shadow">
                    <div class="card-header py-3">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <label for="" class="m-0 font-weight-bold text-primary">Search</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control text-uppercase" autofocus id="search_classes" class="text-uppercase" placeholder="Type class code or subject code to search....">
                            </div>
                            <div class="col-md-1">
                                <label for="" class="m-0 font-weight-bold text-primary">Section</label>
                            </div>
                            <div class="col-md-3">
                                <select id="section_searchclasses" class="form-control">
                                    <option value="">- select section -</option>
                                    @if ($sections_offered)
                                        @foreach ($sections_offered as $key => $section)
                                            <option value="{{ $section->section->id }}" >{{ $section->section->code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="form_tag_grade">
                            <div class="col-xs-8 col-xs-offset-2 well">
                                <table class="table table-sm table-scroll table-striped table-bordered" id="add_classsubjects_table" style="font-size:14px;">
                                    <thead>
                                        <tr>
                                            <th class="w30">#</th>
                                            <th class="w50">Code</th>
                                            <th class="w120">Section</th>
                                            <th class="w120">Subject</th>
                                            <th>Description</th>
                                            <th class="w50">Units</th>
                                            <th>Schedule</th>
                                            <th class="w50">Size</th>
                                            <th class="w50">Max</th>
                                        </tr>
                                    </thead>
                                    <tbody id="return_searchedclasses">
                                        <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm" id="add_selected_classes">Add Selected Classes</button>
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