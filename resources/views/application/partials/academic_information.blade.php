<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="font-weight-bold text-primary mb-0">Academic Information</h6>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="">
            <div class="row align-items-center mb-1">
                <div class="col-lg-1 d-none d-lg-block">
                    <h6 class="m-0 font-weight-bold text-black text-center">Level</h6>                
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <h6 class="m-0 font-weight-bold text-black text-center">Program</h6>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <h6 class="m-0 font-weight-bold text-black text-center">Name of School</h6>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <h6 class="m-0 font-weight-bold text-black text-center">Address</h6>
                </div>
                <div class="col-lg-2 d-none d-lg-block">
                    <h6 class="m-0 font-weight-bold text-black text-center">Period Covered</h6>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Elementary</label>
                    <h6 class="d-lg-none mb-2 font-weight-bold text-black">Elementary</h6>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="" placeholder="" class="form-control col-sm mb-1" id="" disabled>
                </div>
                <div class="col-lg-3">
                    <label for="elem_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                    <input type="text" name="elem_school" value="{{ old('elem_school', $applicant->academic_info->elem_school ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_school" minlength="2" maxlength="255">
                    @error('elem_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_elem_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="elem_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                    <input type="text" name="elem_address" value="{{ old('elem_address', $applicant->academic_info->elem_address ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_address" minlength="2" maxlength="255">
                    @error('elem_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_elem_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <label for="elem_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                    <input type="text" name="elem_period" value="{{ old('elem_period', $applicant->academic_info->elem_period ?? '') }}" placeholder="0000-0000" class="form-control col-sm mb-1 text-uppercase" id="elem_period" minlength="4" maxlength="50">
                    @error('elem_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_elem_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Junior High</label>
                    <h6 class="d-lg-none mb-2 font-weight-bold text-black">Junior High</h6>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="" placeholder="" class="form-control col-sm mb-1" id="" disabled>
                </div>
                <div class="col-lg-3">
                    <label for="jhs_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                    <input type="text" name="jhs_school" value="{{ old('jhs_school', $applicant->academic_info->jhs_school ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_school" minlength="2" maxlength="255">
                    @error('jhs_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_jhs_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="jhs_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                    <input type="text" name="jhs_address" value="{{ old('jhs_address', $applicant->academic_info->jhs_address ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_address" minlength="2" maxlength="255">
                    @error('jhs_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_jhs_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <label for="jhs_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                    <input type="text" name="jhs_period" value="{{ old('jhs_period', $applicant->academic_info->jhs_period ?? '') }}" placeholder="0000-0000" class="form-control col-sm mb-1 text-uppercase" id="jhs_period" minlength="4" maxlength="50">
                    @error('jhs_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_jhs_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Senior High</label>
                    <h6 class="d-lg-none mb-2 font-weight-bold text-black">Senior High</h6>
                </div>
                <div class="col-lg-3">
                    <div class="row align-items-center mb-1">
                        <div class="col-lg-7">
                            <label for="shs_strand" class="d-lg-none m-0 font-weight-bold text-primary">Strand</label>
                            <select name="shs_strand" class="form-control mb-1" id="shs_strand">
                                <option value="">- select strand -</option>
                                <option value="STEM" @if(old('shs_strand', $applicant->academic_info->shs_strand ?? '') == 'STEM') selected @endif>STEM</option>
                                <option value="ABM" @if(old('shs_strand', $applicant->academic_info->shs_strand ?? '') == 'ABM') selected @endif>ABM</option>
                                <option value="HUMSS" @if(old('shs_strand', $applicant->academic_info->shs_strand ?? '') == 'HUMSS') selected @endif>HUMSS</option>
                                <option value="GAS" @if(old('shs_strand', $applicant->academic_info->shs_strand ?? '') == 'GAS') selected @endif>GAS</option>
                                <option value="TECH-VOC" @if(old('shs_strand', $applicant->academic_info->shs_strand ?? '') == 'TECH-VOC') selected @endif>TECH-VOC</option>                      
                            </select>   
                        </div>
                        <div class="col-lg-5 col-lg pl-lg-0">
                            <label for="shs_techvoc_specify" class="d-lg-none m-0 font-weight-bold text-primary">Tech-Voc Specify</label>
                            <input type="text" name="shs_techvoc_specify" readonly value="{{ old('shs_techvoc_specify', $applicant->academic_info->shs_techvoc_specify ?? '') }}" placeholder="TV-SPECIFY" class="form-control col-sm mb-1 text-uppercase" id="shs_techvoc_specify" minlength="2" maxlength="255">             
                        </div>
                    </div>  
                    @error('shs_strand')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_strand" class="errors"></div> 
                    @error('shs_techvoc_specify')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_techvoc_specify" class="errors"></div>                           
                </div>
                <div class="col-lg-3">
                    <label for="shs_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                    <input type="text" name="shs_school" value="{{ old('shs_school', $applicant->academic_info->shs_school ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_school" minlength="2" maxlength="255">
                    @error('shs_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="shs_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                    <input type="text" name="shs_address" value="{{ old('shs_address', $applicant->academic_info->shs_address ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_address" minlength="2" maxlength="255">
                    @error('shs_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <label for="shs_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                    <input type="text" name="shs_period" value="{{ old('shs_period', $applicant->academic_info->shs_period ?? '') }}" placeholder="0000-0000" class="form-control col-sm mb-1 text-uppercase" id="shs_period" minlength="4" maxlength="50">
                    @error('shs_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">College</label>
                    <h6 class="d-lg-none mb-2 font-weight-bold text-black">College</h6>
                </div>
                <div class="col-lg-3">
                    <label for="college_program" class="d-lg-none m-0 font-weight-bold text-primary">Program</label>
                    <input type="text" name="college_program" value="{{ old('college_program', $applicant->academic_info->college_program ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_program" minlength="2" maxlength="255">
                    @error('college_program')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_program" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="college_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                    <input type="text" name="college_school" value="{{ old('college_school', $applicant->academic_info->college_school ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_school" minlength="2" maxlength="255">
                    @error('college_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="college_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                    <input type="text" name="college_address" value="{{ old('college_address', $applicant->academic_info->college_address ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_address" minlength="2" maxlength="255">
                    @error('college_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <label for="college_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                    <input type="text" name="college_period" value="{{ old('college_period', $applicant->academic_info->college_period ?? '') }}" placeholder="0000-0000" class="form-control col-sm mb-1 text-uppercase" id="college_period" minlength="4" maxlength="50">
                    @error('college_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label  class="d-none d-lg-block m-0 font-weight-bold text-primary">Graduate</label>
                    <h6 class="d-lg-none mb-2 font-weight-bold text-black">Graduate Studies</h6>
                </div>
                <div class="col-lg-3">
                    <label for="graduate_program" class="d-lg-none m-0 font-weight-bold text-primary">Program</label>
                    <input type="text" name="graduate_program" value="{{ old('graduate_program', $applicant->academic_info->graduate_program ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_program" minlength="2" maxlength="255">
                    @error('graduate_program')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="graduate_program" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="graduate_school" class="d-lg-none m-0 font-weight-bold text-primary">School Name</label>
                    <input type="text" name="graduate_school" value="{{ old('graduate_school', $applicant->academic_info->graduate_school ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_school" minlength="2" maxlength="255">
                    @error('graduate_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_graduate_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <label for="graduate_address" class="d-lg-none m-0 font-weight-bold text-primary">Address</label>
                    <input type="text" name="graduate_address" value="{{ old('graduate_address', $applicant->academic_info->graduate_address ?? '') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_address" minlength="2" maxlength="255">
                    @error('graduate_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_graduate_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <label for="graduate_period" class="d-lg-none m-0 font-weight-bold text-primary">Period Covered</label>
                    <input type="text" name="graduate_period" value="{{ old('graduate_period', $applicant->academic_info->graduate_period ?? '') }}" placeholder="0000-0000" class="form-control col-sm mb-1 text-uppercase" id="graduate_period" minlength="4" maxlength="50">
                    @error('graduate_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_graduate_period" class="errors"></div>
                </div>
            </div>
            
        </div>
    </div>
</div>