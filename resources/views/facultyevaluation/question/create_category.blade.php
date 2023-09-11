xxxx <!-- Logout Modal-->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
           <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add New Category</h1>
           <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
           </button>
        </div>
        <div class="modal-body">
           <div class="container py-0 px-0">       
               <div class="row">
                   <div class="col-lg-12 mx-auto">
                       <div class="bg-white rounded-lg shadow-sm px-3 pb-3">
                           <p class="mb-0">Add new record in the database</p>
                           <p class="font-italic text-info">Note: (*) Denotes field is required.</p>
                           <!-- credit card info-->
                           <div id="nav-tab-card" class="tab-pane fade show active">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                <form method="POST" action="{{ route('savecategory') }}" id="form_addcategory" role="form">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="name" class="m-0 font-weight-bold text-primary">* Category</label>
                                                <input type="text" name="name" value="" placeholder="" class="form-control" id="name">
                                                <div id="error_name" class="errors"></div>
                                                <div id="error_field" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="field" id="field" value="{{ $field }}">
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Category</button>
                                </form>
                                <div class="table-responsive mt-3" id="">
                                    <table class="table table-bordered table-sm table-striped table-hover" id="" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="w50">#</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="category_body">
                                            @if ($categories !== null && count($categories) > 0)
                                                @foreach ($categories as $category)
                                                    <tr>
                                                        <td class="">{{ $loop->iteration }}</td>
                                                        <td class="">{{ $category->name }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <th class="">&nbsp;</th>
                                                    <th class="">&nbsp;</th>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                           </div>
                           <!-- End -->
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