 <!-- Logout Modal-->
 <div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog modal-xl" role="document" style="max-width: 80% !important">
     <div class="modal-content">
         <div class="modal-header">
            <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Search and Add Class Subjects</h1>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class=" py-0 px-3"> 
                <div class="card shadow">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="" class="m-0 font-weight-bold text-primary">Search Here</label>
                            </div>
                            <div class="col-md-5">
                                
                            </div>
                            <div class="col-md-2">
                                <label for="" class="m-0 font-weight-bold text-primary">Section</label>
                            </div>
                            <div class="col-md-3">
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="form_tag_grade">
                            <div class="col-xs-8 col-xs-offset-2 well">
                                <table class="table table-sm table-scroll table-striped table-bordered" style="font-size:14px;">
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
                                    <tbody id="return_taggrade">
                                        <tr><td colspan="9" class="mid">No records to be displayed!</td></tr>
                                    </tbody>
                                </table>
                            </div>
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