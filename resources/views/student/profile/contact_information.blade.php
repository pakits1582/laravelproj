<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="font-weight-bold text-primary mb-0">Address Information</h6>

    </div>
    <div class="card-body" id="">
        <div class="form-group" id="postcharge_fees">
            <p class="font-italic font-weight-bold text-info mb-0">Note: Please provide your correct contact addresses.</p>
            <div class="row align-items-center mb-3">
                <div class="col-md-12">
                    <h6 class="m-0 font-weight-bold text-black">Current Address</h6>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="current_region" class="m-0 font-weight-bold text-primary">* Region</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    @php
                        if(isset($student))
                        {
                            $current_region_code   = Helpers::findCode($regions, 'region_name', $student->contact_info->current_region, 'region_code');
                            $current_province_code = Helpers::findCode($provinces, 'province_name', $student->contact_info->current_province, 'province_code', $current_region_code, 'region_code');
                            $current_city_code     = Helpers::findCode($cities, 'city_name', $student->contact_info->current_municipality, 'city_code', $current_province_code, 'province_code');

                            $current_provinces = Helpers::findItemsByCode($provinces, "region_code", $current_region_code);
                            $current_cities    = Helpers::findItemsByCode($cities, "province_code", $current_province_code);
                            $current_barangays = Helpers::findItemsByCode($barangays, "city_code", $current_city_code);

                            $permanent_region_code   = Helpers::findCode($regions, 'region_name', $student->contact_info->permanent_region, 'region_code');
                            $permanent_province_code = Helpers::findCode($provinces, 'province_name', $student->contact_info->permanent_province, 'province_code', $permanent_region_code, 'region_code');
                            $permanent_city_code     = Helpers::findCode($cities, 'city_name', $student->contact_info->permanent_municipality, 'city_code', $permanent_province_code, 'province_code');

                            $permanent_provinces = Helpers::findItemsByCode($provinces, "region_code", $permanent_region_code);
                            $permanent_cities    = Helpers::findItemsByCode($cities, "province_code", $permanent_province_code);
                            $permanent_barangays = Helpers::findItemsByCode($barangays, "city_code", $permanent_city_code);
                        }
                    @endphp
                    <select name="current_region" required class="form-control text-uppercase region" id="current_region">
                        <option value="">- select region -</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region['region_name'] }}" data-code="{{ $region['region_code'] }}"
                            @if(old('current_region', $student->contact_info->current_region ?? '') == $region['region_name']) selected @endif
                            >
                                {{ $region['region_name'] }}
                            </option>
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
                        @if(isset($student) && $current_provinces)
                            @foreach ($current_provinces as $province)
                                <option value="{{ $province['province_name'] }}" data-code="{{ $province['province_code'] }}"
                                @if(old('current_province', $student->contact_info->current_province ?? '') == $province['province_name']) selected @endif
                                >
                                    {{ $province['province_name'] }}
                                </option>
                            @endforeach
                        @endif
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
                        @if(isset($student) && $current_cities)   
                            @foreach ($current_cities as $city) 
                                <option value="{{ $city['city_name'] }}" data-code="{{ $city['city_code'] }}"
                                @if(old('current_city', $student->contact_info->current_municipality ?? '') == $city['city_name']) selected @endif
                                >
                                    {{ $city['city_name'] }}
                                </option>
                            @endforeach
                        @endif               
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
                        @if(isset($student) && $current_barangays)   
                            @foreach ($current_barangays as $barangay) 
                                <option value="{{ $barangay['brgy_name'] }}" data-code="{{ $barangay['brgy_code'] }}"
                                @if(old('current_barangay', $student->contact_info->current_barangay ?? '') == $barangay['brgy_name']) selected @endif
                                >
                                    {{ $barangay['brgy_name'] }}
                                </option>
                            @endforeach
                        @endif   
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
                    <input type="text" name="current_address" value="{{ old('current_address', $student->contact_info->current_address ?? '') }}" placeholder="(House#, Street, Subd./Village)" required placeholder="" class="form-control text-uppercase" id="current_address" minlength="2" maxlength="255">
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
                    <input type="text" name="current_zipcode" value="{{ old('current_zipcode', $student->contact_info->current_zipcode ?? '') }}" placeholder="" class="form-control" id="current_zipcode" minlength="2" maxlength="20">
                    @error('current_zipcode')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_current_zipcode" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-12">
                    <h6 class="m-0 font-weight-bold text-black">Permanent Address</h6>
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
                            <option value="{{ $region['region_name'] }}" data-code="{{ $region['region_code'] }}"
                            @if(old('permanent_region', $student->contact_info->permanent_region ?? '') == $region['region_name']) selected @endif
                            >
                                {{ $region['region_name'] }}
                            </option>
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
                        @if(isset($student) && $permanent_provinces)
                            @foreach ($permanent_provinces as $province)
                                <option value="{{ $province['province_name'] }}" data-code="{{ $province['province_code'] }}"
                                @if(old('permanent_province', $student->contact_info->permanent_province ?? '') == $province['province_name']) selected @endif
                                >
                                    {{ $province['province_name'] }}
                                </option>
                            @endforeach
                        @endif                               
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
                        @if(isset($student) && $permanent_cities)   
                            @foreach ($permanent_cities as $city) 
                                <option value="{{ $city['city_name'] }}" data-code="{{ $city['city_code'] }}"
                                @if(old('permanent_city', $student->contact_info->permanent_municipality ?? '') == $city['city_name']) selected @endif
                                >
                                    {{ $city['city_name'] }}
                                </option>
                            @endforeach
                        @endif                                          
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
                        @if(isset($student) && $permanent_barangays)   
                            @foreach ($permanent_barangays as $barangay) 
                                <option value="{{ $barangay['brgy_name'] }}" data-code="{{ $barangay['brgy_code'] }}"
                                @if(old('current_barangay', $student->contact_info->permanent_barangay ?? '') == $barangay['brgy_name']) selected @endif
                                >
                                    {{ $barangay['brgy_name'] }}
                                </option>
                            @endforeach
                        @endif                                  
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
                    <input type="text" name="permanent_address" value="{{ old('permanent_address', $student->contact_info->permanent_address ?? '') }}" placeholder="(House#, Street, Subd./Village)" placeholder="" class="form-control text-uppercase" id="permanent_address" minlength="2" maxlength="255">
                    @error('permanent_address')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_address" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="permanent_zipcode" class="m-0 font-weight-bold text-primary">Zip Code</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="permanent_zipcode" value="{{ old('permanent_zipcode', $student->contact_info->permanent_zipcode ?? '') }}" placeholder="" class="form-control" id="permanent_zipcode" minlength="2" maxlength="20">
                    @error('permanent_zipcode')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_permanent_zipcode" class="errors"></div>
                </div>
            </div>
        </div>
    </div>
</div>