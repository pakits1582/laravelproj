@if(isset($class['merged']))
    @if (count($class['merged']) > 0)
        @foreach ($class['merged'] as $class)
            <tr id="remove_{{ $class['id'] }}">
                <td class="w50 mid">
                    <a href="#" class="btn btn-danger btn-circle btn-sm unmerge_class_subject" id="{{ $class['id'] }}" title="Unmerge Subject">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
                <td class="w80">{{ $class['code'] }}</td>
                <td class="w150">{{ $class['sectioninfo']['code'] }}</td>
                <td class="w150">{{ $class['curriculumsubject']['subjectinfo']['code'] }}</td>
                <td class="">{{ $class['schedule']['schedule'] }}</td>
                @php
                    $faculty = '';
                @endphp
                @if ($class['instructor_id'])
                    @php
                        $fname = explode(" ", $class['instructor']['first_name']);
                        $acronym = "";
                        foreach ($fname as $w) {
                            $acronym .= $w[0];
                        }
                        $callname = ($class['instructor']['first_name'] === '(TBA)') ? 'TBA' : $acronym.'. '.$class['instructor']['last_name'];

                        $faculty = $callname;
                    @endphp
                @endif
                <td class="w150">{{ $faculty }}</td>
                <td class="w100 mid">{{ isset($class['enrolledstudents']['enrollment']) ? count($class['enrolledstudents']['enrollment']) : 0 }}</td>
                <td class="w100 mid">{{ $class['slots'] }}</td>
            </tr>
        @endforeach
    @else
        <tr class="">
            <td class="mid" colspan="8">No records to be displayed</td>
        </tr>
    @endif
    
@else 
    <tr class="">
        <td class="mid" colspan="8">No records to be displayed</td>
    </tr>
@endif

