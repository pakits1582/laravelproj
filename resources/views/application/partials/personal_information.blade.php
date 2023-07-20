<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Personal Information</h4>
    </div>
    <div class="card-body" id="">
        <div class="form-group" id="postcharge_fees">
            <p class="font-italic text-info">Note: LEGAL NAME (Name on Birth Certificate)</p>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="last_name" class="m-0 font-weight-bold text-primary">* Last Name</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="last_name" required placeholder="" class="form-control text-uppercase" id="last_name">
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="first_name" class="m-0 font-weight-bold text-primary">* First Name</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="first_name" required placeholder="" class="form-control text-uppercase" id="first_name">
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="middle_name" class="m-0 font-weight-bold text-primary">Middle Name</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="middle_name" placeholder="" class="form-control text-uppercase" id="middle_name">
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="name_suffix" class="m-0 font-weight-bold text-primary">Name Suffix</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="name_suffix" class="form-control text-uppercase" id="name_suffix">
                        <option value="">- select name suffix -</option>
                        <option value="JR">JR</option>
                        <option value="SR">SR</option>
                        @for ($x=1;$x<15;$x++)
                            <option value="{{ Helpers::romanic_number($x) }}">{{ Helpers::romanic_number($x) }}</option>
                        @endfor 
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="sex" class="m-0 font-weight-bold text-primary">* Sex</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="sex" required class="form-control" id="sex">
                        <option value="1">MALE</option>
                        <option value="2">FEMALE</option>                               
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="civil_status" class="m-0 font-weight-bold text-primary">* Civil Status</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="civil_status" required class="form-control" id="civil_status">
                        <option value="SINGLE">SINGLE</option>
                        <option value="MARRIED">MARRIED</option>
                        <option value="WIDOW">WIDOW</option>
                        <option value="WIDOWER">WIDOWER</option>
                        <option value="SEPARATED">SEPARATED</option>
                        <option value="DIVORCED">DIVORCED</option>
                        <option value="ANNULED">ANNULED</option>                  
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="birth_date" class="m-0 font-weight-bold text-primary">* Birth Date</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="birth_date" required placeholder="" class="form-control" id="birth_date">
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="birth_place" class="m-0 font-weight-bold text-primary">Birth Place</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="birth_place" placeholder="(Municipality, Province)" class="form-control text-uppercase" id="birth_place">
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="nationality" class="m-0 font-weight-bold text-primary">* Nationality</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="nationality" required class="form-control text-uppercase" id="nationality">
                        @foreach (Helpers::nationalities() as $nationality)
                         <option value="{{ $nationality }}">{{ $nationality }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-1">
                <div class="col-md-3 col-sm-3">
                    <label for="religion" class="m-0 font-weight-bold text-primary">* Religion</label>
                </div>
                <div class="col-md-9 col-sm-9">
                    <select name="religion" required class="form-control text-uppercase" id="religion">
                        <option value="">- select religion -</option>
                        <option value = "17">Roman Catholic</option>
                        <option value = "1">Anglican</option>
                        <option value = "2">Aglipayan</option>
                        <option value = "3">Assembly of God</option>
                        <option value = "4">Baptist</option>
                        <option value = "5">Born Again Christian</option>
                        <option value = "6">Church of Latter-Day Saints (Mormons)</option>
                        <option value = "7">Crusaders of the Divine Church of Christ</option>
                        <option value = "8">Iglesia Filipina Independiente</option>
                        <option value = "9">Iglesia Ni Cristo</option>
                        <option value = "10">Islam</option>
                        <option value = "11">Jehovah's Witness</option>
                        <option value = "12">Lutheran</option>
                        <option value = "13">Methodist</option>
                        <option value = "15">Pentecost</option>
                        <option value = "16">Protestant</option>
                        <option value = "18">Seventh Day Adventist</option>
                        <option value = "19">Sikh</option>
                        <option value = "20">UCCP</option>
                        <option value = "14">Others</option>                                                                
                    </select>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-3 col-sm-3"></div>
                <div class="col-md-9 col-sm-9">
                    <input type="text" name="religion_specify" disabled placeholder="IF OTHERS, PLEASE SPECIFY" class="form-control text-uppercase" id="religion_specify">
                </div>
            </div>

            <p class="font-italic text-info">Please mark whether you have received the following sacraments: (Mark N/A if no applicable)</p>

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