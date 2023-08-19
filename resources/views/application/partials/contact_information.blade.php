<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Contact Information</h4>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="postcharge_fees">
            <p class="font-italic font-weight-bold text-info mb-0">Note: Please provide your correct contact information and addresses.</p>
            <div class="row align-items-center mb-3">
                <div class="col-md-12">
                    <h5 class="m-0 font-weight-bold text-black">Current Address</h5>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="current_region" class="m-0 font-weight-bold text-primary">* Region</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="current_region" required class="form-control text-uppercase region" id="current_region">
                        <option value="">- select region -</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region['region_name'] }}" data-code="{{ $region['region_code'] }}">{{ $region['region_name'] }}</option>
                        @endforeach                               
                    </select>
                    @error('current_region')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_region" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="current_province" class="m-0 font-weight-bold text-primary">* Province</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="current_province" class="form-control text-uppercase province" id="current_province">
                        <option value="">- select province -</option>                               
                    </select>
                    @error('current_province')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_province" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="current_municipality" class="m-0 font-weight-bold text-primary">* City/Municipality</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="current_municipality" required class="form-control text-uppercase municipality" id="current_municipality">
                        <option value="">- select municipality -</option>                               
                    </select>
                    @error('current_municipality')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_municipality" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="current_barangay" class="m-0 font-weight-bold text-primary">* Barangay</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="current_barangay" required class="form-control text-uppercase barangay" id="current_barangay">
                        <option value="">- select barangay -</option>                               
                    </select>
                    @error('current_barangay')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_barangay" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="current_address" class="m-0 font-weight-bold text-primary">* House #, Street</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="current_address" value="{{ old('current_address') }}" placeholder="(House#, Street, Subd./Village)" required placeholder="" class="form-control text-uppercase" id="current_address" minlength="2" maxlength="255">
                    @error('current_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_address" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-3 col-sm-3">
                    <label for="current_zipcode" class="m-0 font-weight-bold text-primary">* Zip Code</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="current_zipcode" value="{{ old('current_zipcode') }}" placeholder="" class="form-control" id="current_zipcode" minlength="2" maxlength="20">
                    @error('current_zipcode')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_zipcode" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-12">
                    <h5 class="m-0 font-weight-bold text-black">Permanent Address</h5>
                    <p class="font-italic font-weight-bold text-info mb-0">Note: (If not the same as current address)
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_region" class="m-0 font-weight-bold text-primary">Region</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="permanent_region" class="form-control text-uppercase region" id="permanent_region">
                        <option value="">- select region -</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region['region_name'] }}" data-code="{{ $region['region_code'] }}">{{ $region['region_name'] }}</option>
                        @endforeach                                        
                    </select>
                    @error('permanent_region')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_region" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_province" class="m-0 font-weight-bold text-primary">Province</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="permanent_province" class="form-control text-uppercase province" id="permanent_province">
                        <option value="">- select province -</option>                               
                    </select>
                    @error('permanent_province')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_province" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_municipality" class="m-0 font-weight-bold text-primary">City/Municipality</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="permanent_municipality" class="form-control text-uppercase municipality" id="permanent_municipality">
                        <option value="">- select municipality -</option>                               
                    </select>
                    @error('permanent_municipality')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_municipality" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_barangay" class="m-0 font-weight-bold text-primary">Barangay</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="permanent_barangay" class="form-control text-uppercase barangay" id="permanent_barangay">
                        <option value="">- select barangay -</option>                               
                    </select>
                    @error('permanent_barangay')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_barangay" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_address" class="m-0 font-weight-bold text-primary">House #, Street</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="permanent_address" value="{{ old('permanent_address') }}" placeholder="(House#, Street, Subd./Village)" placeholder="" class="form-control text-uppercase" id="permanent_address" minlength="2" maxlength="255">
                    @error('permanent_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_address" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_zipcode" class="m-0 font-weight-bold text-primary">Zip Code</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="permanent_zipcode" value="{{ old('permanent_zipcode') }}" placeholder="" class="form-control" id="permanent_zipcode" minlength="2" maxlength="20">
                    @error('permanent_zipcode')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_zipcode" class="errors"></div>
                </div>
            </div>

            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="telno" class="m-0 font-weight-bold text-primary">Telephone No.</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="telno" value="{{ old('telno') }}" placeholder="" class="form-control" id="telno" minlength="4" maxlength="20">
                    @error('telno')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_telno" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="mobileno" class="m-0 font-weight-bold text-primary">* Mobile No.</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="mobileno" value="{{ old('mobileno') }}" required placeholder="09XXXXXXXXX" class="form-control" id="mobileno" minlength="11" maxlength="20">
                    @error('mobileno')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_mobileno" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="email" class="m-0 font-weight-bold text-primary">* E-mail Address</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="" class="form-control" id="email" maxlength="150">
                    @error('email')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_email" class="errors"></div>
                </div>
            </div>
        </div>
    </div>
</div>