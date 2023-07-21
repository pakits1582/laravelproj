<div class="row mx-3">
    <div class="col-lg-6 my-3">
        <div class="card border-left-info h-100">
            <div class="card-body p-2">
                <h4 class="mb-2 font-weight-bold text-primary">Instructions</h4>
                <p class="pl-3 font-italic font-weight-bold text-info">Fill out this form carefully and type all information requested. Write N/A if the information is not applicable to you. Omissions can delay the processing of your application.</p>
                <p class="pl-3 font-italic font-weight-bold text-info">INCOMPLETE APPLICATION FORMS WILL NOT BE PROCESSED.</p>
                <p class="pl-3 font-italic font-weight-bold text-info">(*) Denotes required fields, you may opt to skip filling up fields without an asterisk.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 my-3">
        <div class="card border-left-info h-100">
            <div class="card-body p-2 align-items-end">
                <p class="mb-0 font-italic font-weight-bold text-info">Please submit your old ID number if you are a graduate or returnee from this institution and you are applying to a new program.</p>
                <div class="row pt-3">
                    <div class="col-md-9 col-sm-12">
                        <label for="idno" class="m-0 font-weight-bold text-primary">ID Number</label>
                        <input type="text" name="idno" placeholder="" class="form-control" id="idno" minlength="8" maxlength="10">
                        @error('idno')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="col-md-3 d-none d-lg-block"></div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-sm-12">
                        <label for="classification" class="m-0 font-weight-bold text-primary">* Classification</label>
                        <select name="classification" required class="form-control text-uppercase" id="classification">
                            <option value="">- SELECT CLASSIFICATION -</option>
                            <option value="1">NEW STUDENT</option>
                            <option value="2">TRANSFEREE</option>
                            <option value="3">READMIT</option>
                            <option value="4">GRADUATED (New Program)</option>
                        </select>
                        @error('classification')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="col-md-3 d-none d-lg-block"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="program_id" class="m-0 font-weight-bold text-primary">* Academic Program</label>
                        <select name="program_id" required class="form-control text-uppercase" id="program_id">
                            <option value="">- SELECT PROGRAM -</option>
                            @php
                                $groupName = '';
                                $first = true;
                            @endphp
                            @if($programs)
                                @foreach($programs as $program)
                                    @if ($program->level->level != $groupName)
                                        @php
                                            $groupName = $program->level->level; // Just set the new group name
                                        @endphp

                                        @if (!$first) <!-- Add a closing tag when we change the group, but only if we're not in the first loop -->
                                            </optgroup>
                                        @else
                                            @php
                                                $first = false; // Make sure we don't close the tag first time, but do after the first loop
                                            @endphp
                                        @endif
                                        <optgroup label="{{ $groupName }}">
                                    @endif
                                    <option value="{{ $program->id }}">
                                        ({{ $program->code }}) - {{ $program->name }}
                                    </option>
                                @endforeach
                                @if (!$first) <!-- Add a closing tag after the last loop -->
                                    </optgroup>
                                @endif
                            @endif
                        </select>
                        @error('program_id')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>