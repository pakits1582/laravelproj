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
                    <input type="text" name="elem_school" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_school">
                    @error('elem_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="elem_address" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_address">
                    @error('elem_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-2">
                    <input type="text" name="elem_period" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="elem_period">
                    @error('elem_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
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
                    <input type="text" name="jhs_school" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_school">
                    @error('jhs_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="jhs_address" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_address">
                    @error('jhs_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-2">
                    <input type="text" name="jhs_period" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="jhs_period">
                    @error('jhs_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
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
                            <input type="text" name="shs_techvoc_specify" placeholder="TV-SPECIFY" class="form-control col-sm mb-1 text-uppercase" id="shs_techvoc_specify">             
                        </div>
                        @error('shs_strand')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>                    
                </div>
                <div class="col-lg-3">
                    <input type="text" name="shs_school" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_school">
                    @error('shs_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="shs_address" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_address">
                    @error('shs_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-2">
                    <input type="text" name="shs_period" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="shs_period">
                    @error('shs_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="college_program" class="m-0 font-weight-bold text-primary">College</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="college_program" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_program">
                    @error('college_program')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="college_school" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_school">
                    @error('college_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="college_address" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_address">
                    @error('college_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-2">
                    <input type="text" name="college_period" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="college_period">
                    @error('college_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
            </div>
            
            <div class="row align-items-center mb-1">
                <div class="col-lg-1">
                    <label for="term" class="m-0 font-weight-bold text-primary">Graduate</label>
                </div>
                <div class="col-lg-3">
                    <input type="text" name="gradute_program" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="gradute_program">
                    @error('gradute_program')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="graduate_school" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_school">
                    @error('graduate_school')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <input type="text" name="graduate_address" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_address">
                    @error('graduate_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="col-lg-2">
                    <input type="text" name="graduate_period" placeholder="" class="form-control col-sm mb-1 text-uppercase" id="graduate_period">
                    @error('graduate_period')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
            </div>
            
        </div>
    </div>
</div>