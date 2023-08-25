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
                <p class="mb-3 font-italic font-weight-bold text-info">Please submit your old ID number if you are a graduate or returnee from this institution and you are applying to a new program.</p>
                @if ($withperiod)
                    <div class="row">
                        <div class="col-md-12">
                            <label for="entry_period" class="m-0 font-weight-bold text-primary">* Application Period</label>
                            <select name="entry_period" required class="form-control" id="entry_period">
                                @if ($periods)
                                    @foreach ($periods as $period)
                                        <option value="{{ $period->id }}" {{ ($period->id === session('current_period')) ? 'selected' : '' }}>{{ $period->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('entry_period')
                                <p class="text-danger text-xs mt-1">{{$message}}</p>
                            @enderror
                            <div id="error_entry_period" class="errors"></div>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="entry_period" required value="{{ $configuration->applicationperiod->id ?? session('current_period') }}" />
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <label for="idno" class="m-0 font-weight-bold text-primary">ID Number</label>
                        <input type="text" name="idno" value="{{ old('idno', $applicant->user->idno ?? '') }}" placeholder="" class="form-control" id="idno">
                        @error('idno')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        <div id="error_idno" class="errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="classification" class="m-0 font-weight-bold text-primary">* Classification</label>
                        <select name="classification" required class="form-control text-uppercase" id="classification">
                            <option value="">- SELECT CLASSIFICATION -</option>
                            @foreach(\App\Models\Student::STUDENT_CLASSIFICATION as $key => $val)
                                <option value="{{ $key }}" @if(old('classification', $applicant->classification ?? '') ==  $key) selected @endif>{{ $val }}</option>
                            @endforeach
                        </select>
                        @error('classification')
                            <p class="text-danger text-xs mt-1">{{$message}}</p>
                        @enderror
                        <div id="error_classification" class="errors"></div>
                    </div>
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
                                    <option value="{{ $program->id }}"
                                        @if(old('program_id', $applicant->program_id ?? '') == $program->id) selected @endif
                                    >
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
                        <div id="error_program_id" class="errors"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>