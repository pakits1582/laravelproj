<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="font-weight-bold text-primary mb-0">Family Information</h6>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="card border-left-info h-100">
                        <div class="card-body p-2">                    
                            <div class="row align-items-center">
                                <div class="col-lg-5 col-sm-5">
                                    <h6 class="mb-2 font-weight-bold text-black">Father</h6>
                                </div>
                                <div class="col-lg-7 col-sm-7">
                                    <label for="fatheralive"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="father_alive" value="1" id="fatheralive"  @if(!isset($applicant) || $applicant->personal_info->father_alive == 1) checked @endif> Living </label>
                                    <label for="fatherdeceased"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="father_alive" value="2" id="fatherdeceased"  @if(isset($applicant) && $applicant->personal_info->father_alive == 2) checked @endif> Deceased </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="father_name" class="m-0 font-weight-bold text-primary">Name</label>
                                    <input type="text" name="father_name" value="{{ old('father_name', $applicant->personal_info->father_name ?? '') }}" placeholder="" class="form-control text-uppercase" id="father_name" maxlength="255" minlength="2">
                                    @error('father_name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_father_name" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="father_contactno" class="m-0 font-weight-bold text-primary">Contact Number</label>
                                    <input type="text" name="father_contactno" value="{{ old('father_contactno', $applicant->personal_info->father_contactno ?? '') }}" placeholder="" class="form-control text-uppercase" id="father_contactno" minlength="4" maxlength="30">
                                    @error('father_contactno')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_father_contactno" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="father_address" class="m-0 font-weight-bold text-primary">Home Address</label>
                                    <input type="text" name="father_address" value="{{ old('father_address', $applicant->personal_info->father_address ?? '') }}" placeholder="" class="form-control text-uppercase" id="father_address" maxlength="255" minlength="2">
                                    @error('father_address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_father_address" class="errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-lg-5 col-sm-5">
                                    <h6 class="mb-2 font-weight-bold text-black">Mother</h6>
                                </div>
                                <div class="col-lg-7 col-sm-7">
                                    <label for="motheralive" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="mother_alive" value="1" id="motheralive"  @if(!isset($applicant) || $applicant->personal_info->mother_alive == 1) checked @endif> Living </label>
                                    <label for="motherdeceased" class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="mother_alive" value="2" id="motherdeceased" @if(isset($applicant) && $applicant->personal_info->mother_alive == 2) checked @endif> Deceased </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="mother_name" class="m-0 font-weight-bold text-primary">Name</label>
                                    <input type="text" name="mother_name" value="{{ old('mother_name', $applicant->personal_info->mother_name ?? '') }}" placeholder="" class="form-control text-uppercase" id="mother_name" minlength="2" maxlength="255">
                                    @error('mother_name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_mother_name" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="mother_contactno" class="m-0 font-weight-bold text-primary">Contact Number</label>
                                    <input type="text" name="mother_contactno" value="{{ old('mother_contactno', $applicant->personal_info->mother_contactno ?? '') }}" placeholder="" class="form-control" id="mother_contactno" minlength="2" maxlength="30">
                                    @error('mother_contactno')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_mother_contactno" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="mother_address" class="m-0 font-weight-bold text-primary">Home Address</label>
                                    <input type="text" name="mother_address" value="{{ old('mother_address', $applicant->personal_info->mother_address ?? '') }}" placeholder="" class="form-control text-uppercase" id="mother_address" minlength="2" maxlength="255">
                                    @error('mother_address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_mother_address" class="errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="card border-left-info h-100">
                        <div class="card-body p-2">
                            <h6 class="mb-2 font-weight-bold text-black">Guardian/Spouse (If married)</h6>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="guardian_name" class="m-0 font-weight-bold text-primary">Name</label>
                                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $applicant->personal_info->guardian_name ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_name" minlength="2" maxlength="255">
                                    @error('guardian_name')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_name" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="guardian_relationship" class="m-0 font-weight-bold text-primary">Relationship</label>
                                    <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $applicant->personal_info->guardian_relationship ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_relationship" minlength="2" maxlength="100">
                                    @error('guardian_relationship')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_relationship" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="guardian_contactno" class="m-0 font-weight-bold text-primary">Contact Number</label>
                                    <input type="text" name="guardian_contactno" value="{{ old('guardian_contactno', $applicant->personal_info->guardian_contactno ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_contactno" minlength="4" maxlength="30">
                                    @error('guardian_contactno')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_contactno" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="guardian_address" class="m-0 font-weight-bold text-primary">Home Address</label>
                                    <input type="text" name="guardian_address" value="{{ old('guardian_address', $applicant->personal_info->guardian_address ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_address" minlength="2" maxlength="255">
                                    @error('guardian_address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_address" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="guardian_occupation" class="m-0 font-weight-bold text-primary">Occupation</label>
                                    <input type="text" name="guardian_occupation" value="{{ old('guardian_occupation', $applicant->personal_info->guardian_occupation ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_occupation" minlength="2" maxlength="255">
                                    @error('guardian_occupation')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_occupation" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="guardian_employer" class="m-0 font-weight-bold text-primary">Company/Employer</label>
                                    <input type="text" name="guardian_employer" value="{{ old('guardian_employer', $applicant->personal_info->guardian_employer ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_employer" minlength="2" maxlength="255">
                                    @error('guardian_employer')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_employer" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="guardian_employer_address" class="m-0 font-weight-bold text-primary">Company Address</label>
                                    <input type="text" name="guardian_employer_address" value="{{ old('guardian_employer_address', $applicant->personal_info->guardian_employer_address ?? '') }}" placeholder="" class="form-control text-uppercase" id="guardian_employer_address" minlength="2" maxlength="255">
                                    @error('guardian_employer_address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_guardian_employer_address" class="errors"></div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="card border-left-info h-100">
                        <div class="card-body p-2">
                            <h6 class="mb-2 font-weight-bold text-black">Work Details (If applicant is working)</h6>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="occupation" class="m-0 font-weight-bold text-primary">Present Occupation</label>
                                    <input type="text" name="occupation" value="{{ old('occupation', $applicant->personal_info->occupation ?? '') }}" placeholder="" class="form-control text-uppercase" id="occupation" minlength="2" maxlength="255">
                                    @error('occupation')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_occupation" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="employer" class="m-0 font-weight-bold text-primary">Company/Employer</label>
                                    <input type="text" name="employer" value="{{ old('employer', $applicant->personal_info->employer ?? '') }}" placeholder="" class="form-control text-uppercase" id="employer" minlength="2" maxlength="255">
                                    @error('employer')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_employer" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="employer_address" class="m-0 font-weight-bold text-primary">Company Address</label>
                                    <input type="text" name="employer_address" value="{{ old('employer_address', $applicant->personal_info->employer_address ?? '') }}" placeholder="" class="form-control text-uppercase" id="employer_address" minlength="2" maxlength="255">
                                    @error('employer_address')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_employer_address" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="employer_contact" class="m-0 font-weight-bold text-primary">Company Contact No.</label>
                                    <input type="text" name="employer_contact" value="{{ old('employer_contact', $applicant->personal_info->employer_contact ?? '') }}" placeholder="" class="form-control text-uppercase" id="employer_contact" minlength="2" maxlength="30">
                                    @error('employer_contact')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_employer_contact" class="errors"></div>
                                </div>
                                <div class="col-lg-12">
                                    <label for="occupation_years" class="m-0 font-weight-bold text-primary">Years of Service</label>
                                    <input type="text" name="occupation_years" value="{{ old('occupation_years', $applicant->personal_info->occupation_years ?? '') }}" placeholder="" class="form-control" id="occupation_years" minlength="1" maxlength="2">
                                    @error('occupation_years')
                                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                                    @enderror
                                    <div id="error_occupation_years" class="errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>