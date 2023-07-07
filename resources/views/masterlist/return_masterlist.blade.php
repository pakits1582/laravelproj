<table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class="">#</th>
            <th class="">ID No.</th>
            <th class="">Name</th>
            <th class="">Program</th>
            <th class="">Year</th>
            <th class="">Units</th>
        </tr>
    </thead>
    <tbody id="return_masterlist">
        @if (count($masterlist) > 0)
            @foreach ($masterlist as $student)
                <tr class="returned">
                    <td class="">{{ $loop->iteration }}</td>
                    <td class="mid">{{ $student->student->user->idno }}</td>
                    <td class="">{{ $student->student->name }}</td>
                    <td class="">{{ $student->program->code }}</td>
                    <td class="mid">{{ $student->year_level }}</td>
                    <td class="mid">{{ $student->enrolled_units }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
                <th class="">&nbsp;</th>
            </tr>
        @endif
    </tbody>
</table>
