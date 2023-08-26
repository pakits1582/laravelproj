<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Attach Requirements</h4>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="card border-left-info h-100">
                        <div class="card-body p-2">
                            <h4 class="mb-2 font-weight-bold text-black">ID Picture Upload</h4>
                    
                            <div class="row align-items-center">
                                <div class="col-md-5 col-sm-5">
                                    <div id="picture_preview" class="image-upload-preview"></div>
                                </div>
                                <div class="col-md-7 col-sm-7">
                                    <p class="m-0 font-italic text-info">
                                        Note: 
                                        <ul class="m-0 font-italic text-info">
                                            <li>Upload passport size picture with white background</li>
                                            <li>Showing only the head and shoulders</li>
                                        </ul>
                                    </p>   
                                </div>
                            </div>
                            <div class="row align-items-center mt-2">
                                <div class="col-md-12">
                                    <div class="custom-file">
                                        <input type="file" name="picture" class="custom-file-input" id="picture" {{ (isset($required) && $required == false) ? '' : 'required' }} accept="image/*">
                                        <label class="custom-file-label" for="picture">Choose file</label>
                                    </div>
                                    @error('picture')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_picture" class="errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="card border-left-info h-100">
                        <div class="card-body p-2 align-items-end">
                            <h4 class="mb-2 font-weight-bold text-black">Report Card/OTR Upload</h4>
                            <p class="m-0 font-italic text-info">
                                Note: 
                                <ul class="m-0 font-italic text-info">
                                    <li>Upload scanned report Card/OTR (atleast first semester)</li>
                                    <li>Copy of Card/OTR should clearly show all pages and all information</li>
                                    <li>For ALS finishers upload certification from DepEd</li>
                                </ul>
                            </p>   
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" multiple name="report_card[]" id="report_card" {{ (isset($required) && $required == false) ? '' : 'required' }} accept="image/*,application/pdf">
                                        <label class="custom-file-label" for="report_card">Multiple selection user ctrl+click </label>
                                    </div>
                                    @error('report_card')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_report_card" class="errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="card border-left-info h-100">
                        <div class="card-body p-2">
                            <h4 class="mb-2 font-weight-bold text-black">Contact Details</h4>
                            <p class="font-italic text-info">Note: Please provide a working email address where we will send your status of Application and a mobile/landline number for us to reach you.</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="contact_email" class="m-0 font-weight-bold text-primary">* Contact E-mail</label>
                                    <input type="email" name="contact_email" value="{{ old('contact_email', $applicant->contact_info->contact_email ?? '') }}" placeholder="" class="form-control" required id="contact_email">
                                    @error('contact_email')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_contact_email" class="errors"></div>
                                </div>
                                <div class="col-md-12">
                                    <label for="contact_no" class="m-0 font-weight-bold text-primary">* Contact Number</label>
                                    <input type="text" name="contact_no" placeholder="09XXXXXXXXX" value="{{ old('contact_no', $applicant->contact_info->contact_no ?? '') }}" class="form-control" required id="contact_no" minlength="1" maxlength="20">
                                    @error('contact_no')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_contact_no" class="errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>