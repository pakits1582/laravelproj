<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Personal Information</h4>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="postcharge_fees">
            <p class="font-italic font-weight-bold text-info">Note: LEGAL NAME (Name on Birth Certificate)</p>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="last_name" class="m-0 font-weight-bold text-primary">* Last Name</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="" class="form-control text-uppercase" id="last_name" minlength="2" maxlength="255">
                    @error('last_name')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_last_name" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="first_name" class="m-0 font-weight-bold text-primary">* First Name</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="" class="form-control text-uppercase" id="first_name" minlength="2" maxlength="255">
                    @error('first_name')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_first_name" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="middle_name" class="m-0 font-weight-bold text-primary">Middle Name</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="" class="form-control text-uppercase" id="middle_name" minlength="2" maxlength="255">
                    @error('middle_name')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_middle_name" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="name_suffix" class="m-0 font-weight-bold text-primary">Name Suffix</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="name_suffix" class="form-control text-uppercase" id="name_suffix">
                        <option value="">- select name suffix -</option>
                        <option value="JR" @if(old('name_suffix') == 'JR') selected @endif>JR</option>
                        <option value="SR" @if(old('name_suffix') == 'SR') selected @endif>SR</option>
                        @for ($x=1;$x<15;$x++)
                            <option value="{{ Helpers::romanic_number($x) }}"
                                @if(old('name_suffix') == Helpers::romanic_number($x)) selected @endif
                            >{{ Helpers::romanic_number($x) }}</option>
                        @endfor 
                    </select>
                    @error('name_suffix')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_name_suffix" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="sex" class="m-0 font-weight-bold text-primary">* Sex</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="sex" required class="form-control" id="sex">
                        <option value="1" @if(old('sex') == 1) selected @endif>MALE</option>
                        <option value="2"  @if(old('sex') == 2) selected @endif>FEMALE</option>                               
                    </select>
                    @error('sex')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_sex" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="civil_status" class="m-0 font-weight-bold text-primary">* Civil Status</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="civil_status" required class="form-control" id="civil_status">
                        <option value="SINGLE" @if(old('civil_status') == 'SINGLE') selected @endif>SINGLE</option>
                        <option value="MARRIED" @if(old('civil_status') == 'MARRIED') selected @endif>MARRIED</option>
                        <option value="WIDOW" @if(old('civil_status') == 'WIDOW') selected @endif>WIDOW</option>
                        <option value="WIDOWER" @if(old('civil_status') == 'WIDOWER') selected @endif>WIDOWER</option>
                        <option value="SEPARATED" @if(old('civil_status') == 'SEPARATED') selected @endif>SEPARATED</option>
                        <option value="DIVORCED" @if(old('civil_status') == 'DIVORCED') selected @endif>DIVORCED</option>
                        <option value="ANNULED" @if(old('civil_status') == 'ANNULED') selected @endif>ANNULED</option>                  
                    </select>
                    @error('civil_status')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_civil_status" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="birth_date" class="m-0 font-weight-bold text-primary">* Birth Date</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="birth_date" value="{{ old('birth_date') }}" required placeholder="YYYY-MM-DD" class="form-control" id="birth_date">
                    @error('birth_date')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_birth_date" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="birth_place" class="m-0 font-weight-bold text-primary">Birth Place</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="(Municipality, Province)" class="form-control text-uppercase" id="birth_place" minlength="2" maxlength="255">
                    @error('birth_place')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_birth_place" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="nationality" class="m-0 font-weight-bold text-primary">* Nationality</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="nationality" required class="form-control text-uppercase" id="nationality">
                        @foreach (Helpers::nationalities() as $nationality)
                            <option value="{{ $nationality }}"
                                @if(old('nationality') == $nationality) selected @endif
                            >{{ $nationality }}</option>
                        @endforeach
                    </select>
                    @error('nationality')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_nationality" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="religion" class="m-0 font-weight-bold text-primary">* Religion</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="religion" required class="form-control text-uppercase" id="religion">
                        <option value="">- select religion -</option>
                        <option value = "17" @if(old('religion') == '17') selected @endif>Roman Catholic</option>
                        <option value = "1" @if(old('religion') == '1') selected @endif>Anglican</option>
                        <option value = "2" @if(old('religion') == '2') selected @endif>Aglipayan</option>
                        <option value = "3" @if(old('religion') == '3') selected @endif>Assembly of God</option>
                        <option value = "4" @if(old('religion') == '4') selected @endif>Baptist</option>
                        <option value = "5" @if(old('religion') == '5') selected @endif>Born Again Christian</option>
                        <option value = "6" @if(old('religion') == '6') selected @endif>Church of Latter-Day Saints (Mormons)</option>
                        <option value = "7" @if(old('religion') == '7') selected @endif>Crusaders of the Divine Church of Christ</option>
                        <option value = "8" @if(old('religion') == '8') selected @endif>Iglesia Filipina Independiente</option>
                        <option value = "9" @if(old('religion') == '9') selected @endif>Iglesia Ni Cristo</option>
                        <option value = "10" @if(old('religion') == '10') selected @endif>Islam</option>
                        <option value = "11" @if(old('religion') == '11') selected @endif>Jehovah's Witness</option>
                        <option value = "12" @if(old('religion') == '12') selected @endif>Lutheran</option>
                        <option value = "13" @if(old('religion') == '13') selected @endif>Methodist</option>
                        <option value = "15" @if(old('religion') == '15') selected @endif>Pentecost</option>
                        <option value = "16" @if(old('religion') == '16') selected @endif>Protestant</option>
                        <option value = "18" @if(old('religion') == '18') selected @endif>Seventh Day Adventist</option>
                        <option value = "19" @if(old('religion') == '19') selected @endif>Sikh</option>
                        <option value = "20" @if(old('religion') == '20') selected @endif>UCCP</option>
                        <option value = "14" @if(old('religion') == '14') selected @endif>Others</option>                               
                    </select>
                    @error('religion')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_religion" class="errors"></div>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-3 col-sm-3"></div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="religion_specify" value="{{ old('religion_specify') }}" disabled placeholder="IF OTHERS, PLEASE SPECIFY" class="form-control text-uppercase" id="religion_specify"  minlength="2" maxlength="150">
                    @error('religion_specify')
                        <p class="text-danger text-xs mt-1">{{$message}}</p>
                    @enderror
                    <div id="error_religion_specify" class="errors"></div>
                </div>
            </div>

            <p class="font-italic font-weight-bold text-info">Please mark whether you have received the following sacraments: (Mark N/A if no applicable)</p>
            <div class="row align-items-center mb-2">
                <div class="col-md-3 col-sm-3">
                    <label for="term" class="m-0 font-weight-bold text-primary">Baptism</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <label for="baptism_yes"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="baptism" value="1" id="baptism_yes" checked> YES </label>
                    <label for="baptism_no"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="baptism" value="2" id="baptism_no"> NO </label>
                    <label for="baptism_na"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="baptism" value="3" id="baptism_na" checked> N/A </label>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-3 col-sm-3">
                    <label for="term" class="m-0 font-weight-bold text-primary">First Communion</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <label for="communion_yes"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="communion" value="1" id="communion_yes" checked> YES </label>
                    <label for="communion_no"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="communion" value="2" id="communion_no"> NO </label>
                    <label for="communion_na"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="communion" value="3" id="communion_na" checked> N/A </label>
                </div>
            </div>
            <div class="row align-items-center mb-2">
                <div class="col-md-3 col-sm-3">
                    <label for="term" class="m-0 font-weight-bold text-primary">Confirmation</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <label for="confirmation_yes"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="confirmation" value="1" id="confirmation_yes" checked> YES </label>
                    <label for="confirmation_no"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="confirmation" value="2" id="confirmation_no"> NO </label>
                    <label for="confirmation_na"  class="m-0 font-weight-bold text-primary"><input type="radio" class="" name="confirmation" value="3" id="confirmation_na" checked> N/A </label>
                </div>
            </div>
        </div>
    </div>
</div>