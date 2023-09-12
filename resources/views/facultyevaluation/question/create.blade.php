<div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
           <h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Add New Question</h1>
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
                                <form method="POST" action="{{ route('savequestion') }}" id="form_addquestion" role="form">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="educational_level_id" class="m-0 font-weight-bold text-primary">* Level</label>
                                            </div>
                                            <div class="col-md-10">
                                                @include('partials.educlevels.dropdown', ['fieldname' => 'educational_level_id', 'fieldid' => 'educational_level_id', 'fieldclass' => '', 'value' => 1])
                                            </div>
                                            <div id="error_educational_level_id" class="errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="question_category_id" class="m-0 font-weight-bold text-primary">* Category</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="question_category_id" id="question_category_id" class="form-control question_category">
                                                    <option value="">- select category -</option>
                                                    @if ($categories !== null && count($categories) > 0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                    <option value="add_item">- click to add new item -</option>
                                                </select>
                                                <div id="error_question_category_id" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="question_subcategory_id" class="m-0 font-weight-bold text-primary">Sub-category</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="question_subcategory_id" id="question_subcategory_id" class="form-control question_category">
                                                    <option value="">- select sub-category -</option>
                                                    @if ($subcategories !== null && count($subcategories) > 0)
                                                        @foreach ($subcategories as $subcategory)
                                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                                        @endforeach
                                                    @endif
                                                    <option value="add_item">- click to add new item -</option>
                                                </select>
                                                <div id="error_question_subcategory_id" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="question_group_id" class="m-0 font-weight-bold text-primary">Group</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="question_group_id" id="question_group_id" class="form-control question_category">
                                                    <option value="">- select group -</option>
                                                    @if ($groups !== null && count($groups) > 0)
                                                        @foreach ($groups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                        @endforeach
                                                    @endif
                                                    <option value="add_item">- click to add new item -</option>
                                                </select>
                                                <div id="error_question_group_id" class="errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="question"  class="m-0 font-weight-bold text-primary">* Question</label>
                                                <textarea name="question" id="question" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div id="error_question" class="errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <a href="#" id="copy_questions" class="m-0 font-weight-bold text-primary">Click here to copy all questions from other educational level.</a>
                                    </div>
                                    <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">Save Question</button>
                               </form>
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