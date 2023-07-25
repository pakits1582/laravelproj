<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Academic Information</h4>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="">
            <div class="row align-items-center mb-1">
                <div class="col-lg-1 d-none d-lg-block">
                    <h5 class="m-0 font-weight-bold text-success text-center">Level</h5>                
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <h5 class="m-0 font-weight-bold text-success text-center">Program</h5>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <h5 class="m-0 font-weight-bold text-success text-center">Name of School</h5>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <h5 class="m-0 font-weight-bold text-success text-center">Address</h5>
                </div>
                <div class="col-lg-2 d-none d-lg-block">
                    <h5 class="m-0 font-weight-bold text-success text-center">Period Covered</h5>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="term" class="m-0 font-weight-bold text-primary">Elementary</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="" placeholder="" class="form-control col-sm mb-1" id="" disabled>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="elem_school" value="{{ old('elem_school') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_school" minlength="2" maxlength="255">
                    @error('elem_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_elem_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="elem_address" value="{{ old('elem_address') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_address" minlength="2" maxlength="255">
                    @error('elem_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_elem_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <input type="text" name="elem_period" value="{{ old('elem_period') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_period" minlength="4" maxlength="50">
                    @error('elem_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_elem_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="term" class="m-0 font-weight-bold text-primary">Junior High</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="" placeholder="" class="form-control col-sm mb-1" id="" disabled>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="jhs_school" value="{{ old('jhs_school') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_school" minlength="2" maxlength="255">
                    @error('jhs_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_jhs_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="jhs_address" value="{{ old('jhs_address') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_address" minlength="2" maxlength="255">
                    @error('jhs_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_jhs_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <input type="text" name="jhs_period" value="{{ old('jhs_period') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_period" minlength="4" maxlength="50">
                    @error('jhs_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_jhs_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="term" class="m-0 font-weight-bold text-primary">Senior High</label>
                </div>
                <div class="col-lg-3">
                    <div class="row align-items-center mb-1">
                        <div class="col-lg-7">
                            <select name="shs_strand" class="form-control mb-1" id="shs_strand">
                                <option value="">- select strand -</option>
                                <option value="STEM">STEM</option>
                                <option value="ABM">ABM</option>
                                <option value="HUMSS">HUMSS</option>
                                <option value="GAS">GAS</option>
                                <option value="TECH-VOC">TECH-VOC</option>                      
                            </select>   
                        </div>
                        <div class="col-lg-5 col-lg pl-lg-0">
                            <input type="text" name="shs_techvoc_specify" value="{{ old('shs_techvoc_specify') }}" placeholder="TV-SPECIFY" class="form-control col-sm mb-1 text-uppercase" id="shs_techvoc_specify" minlength="2" maxlength="255">             
                        </div>
                    </div>  
                    @error('shs_strand')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_strand" class="errors"></div>                  
                </div>
                <div class="col-lg-3">
                    <input type="text" name="shs_school" value="{{ old('shs_school') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_school" minlength="2" maxlength="255">
                    @error('shs_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="shs_address" value="{{ old('shs_address') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_address" minlength="2" maxlength="255">
                    @error('shs_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <input type="text" name="shs_period" value="{{ old('shs_period') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_period" minlength="4" maxlength="50">
                    @error('shs_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_shs_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="" class="m-0 font-weight-bold text-primary">College</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="college_program" value="{{ old('college_program') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_program" minlength="2" maxlength="255">
                    @error('college_program')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_program" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="college_school" value="{{ old('college_school') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_school" minlength="2" maxlength="255">
                    @error('college_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="college_address" value="{{ old('college_address') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_address" minlength="2" maxlength="255">
                    @error('college_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <input type="text" name="college_period" value="{{ old('college_period') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_period" minlength="4" maxlength="50">
                    @error('college_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_college_period" class="errors"></div>
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="" class="m-0 font-weight-bold text-primary">Graduate</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="graduate_program" value="{{ old('graduate_program') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_program" minlength="2" maxlength="255">
                    @error('graduate_program')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="graduate_program" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="graduate_school" value="{{ old('graduate_school') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_school" minlength="2" maxlength="255">
                    @error('graduate_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_graduate_school" class="errors"></div>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="graduate_address" value="{{ old('graduate_address') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_address" minlength="2" maxlength="255">
                    @error('graduate_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_graduate_address" class="errors"></div>
                </div>
                <div class="col-lg-2">
                    <input type="text" name="graduate_period" value="{{ old('graduate_period') }}" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_period" minlength="4" maxlength="50">
                    @error('graduate_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_graduate_period" class="errors"></div>
                </div>
            </div>
            
        </div>
    </div>
</div>