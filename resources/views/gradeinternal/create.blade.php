<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Internal Grade Form</h6>
            </div>
            <div class="card-body">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <form method="POST" action="{{ route('gradeinternals.store') }}"  role="form" id="form_internalgrade">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year_level" class="m-0 font-weight-bold text-primary">* Subject</label>
                                <select name="subject_id" class="form-control select clearable" id="subject">
                                    <option value="">- search subject -</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="term" class="m-0 font-weight-bold text-primary">Instructor</label>
                            <select name="instructor_id" class="form-control" id="instructor">
                                <option value="">- select instructor -</option>
                                @if ($instructors)
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->last_name.', '.$instructor->first_name.' '.$instructor->middle_name }}</option>
                                    @endforeach
                                @endif
                            </select>                                            
                        </div>
                    </div>
                    <div class="row  align-items-end">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">* Grade</label>
                                <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">C. G.</label>
                                <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="code"  class="m-0 font-weight-bold text-primary">* Units</label>
                                <input type="text" id="curriculum" class="form-control text-uppercase clearable" value="" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mid" id="button_group">
                                <button type="submit" id="save_class" class="btn btn-sm btn-success btn-icon-split mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span class="text">Save Changes</span>
                                </button>
                                <button type="button" id="edit" class="btn btn-sm btn-primary btn-icon-split actions mb-2 mb-md-0" disabled>
                                    <span class="icon text-white-50">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </button>
                                <button type="button" id="delete" class="btn btn-sm btn-danger btn-icon-split actions mb-2 mb-md-0" disabled>
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                                <button type="button" id="cancel" class="btn btn-sm btn-danger btn-icon-split mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancel</span>
                                </button>
                                {{-- <button type="button" id="add_subjects" class="btn btn-sm btn-success btn-icon-split mb-2 mb-md-0">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus-square"></i>
                                    </span>
                                    <span class="text">Add Multiple Subjects</span>
                                </button> --}}
                            </div>                                    
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>