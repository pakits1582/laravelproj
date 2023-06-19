@if (count($grouped_sectiomonitorings) > 0)
    @foreach ($grouped_sectiomonitorings as $grouped_section)
        <h3 class="mb-2 font-weight-bold text-black">({{ $grouped_section['program_code'] }}) {{ $grouped_section['program_name'] }}</h3>
        <div class="table-responsive-sm">
            <table class="table table-sm table-striped table-bordered" style="font-size: 14px;">
                <thead class="">
                    <tr>
                        <th class="w50">#</th>
                        <th class="">Section Code</th>
                        <th class="">Section Name</th>
                        <th class="w100">Year Level</th>
                        <th class="w120">No. of Enrolled</th>
                        <th class="w120">Min. Enrollee</th>
                        <th class="w120">Allowed Units</th>
                        <th class="w150">Status</th>
                        <th class="w150">Action</th>
                    </tr>
                </thead>
                <tbody class="text-black" id="">
                    @if (count($grouped_section['sections']) > 0)
                        @foreach ($grouped_section['sections'] as $section)
                            <tr>
                                <td class="w50">{{ $loop->iteration }}</td>
                                <td class="">{{ $section['section_code'] }}</td>
                                <td class="">{{ $section['section_name'] }}</td>
                                <td class="w100 mid">{{ $section['section_year'] }}</td>
                                <td class="w120 mid"><a href="#" class="font-weight-bold text-black viewenrolledinsection" id="{{ $section['section_id'] }}">{{ $section['enrolled_count'] }}</a></td>
                                <td class="w120 mid tutorial minimum_enrollee" contenteditable="true" id="{{ $section['sectionmonitoring_id'] }}" data-value="{{ $section['minimum_enrollees'] }}">{{ $section['minimum_enrollees'] }}</td>
                                <td class="w120 mid tutorial allowed_units" contenteditable="true" id="{{ $section['sectionmonitoring_id'] }}" data-value="{{ $section['sectionmonitoring_allowed_units'] }}">{{ $section['sectionmonitoring_allowed_units'] }}</td>
                                <td class="w150 mid">{{ ($section['sectionmonitoring_status'] == 1) ? 'OPEN' : 'CLOSED' }}</td>
                                <td class="w150 mid">
                                    @if ($section['sectionmonitoring_status'] == 1)
                                        <a href="#" class="btn btn-danger btn-circle btn-sm close_section" id="{{ $section['sectionmonitoring_id'] }}" title="Close">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    @else
                                    <a href="#" class="btn btn-primary btn-circle btn-sm open_section" id="{{ $section['sectionmonitoring_id'] }}" title="Open">
                                        <i class="fas fa-unlock"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach 
                    @else
                        <tr><td colspan="8">No records to be displayed!</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endforeach
@else
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@endif
