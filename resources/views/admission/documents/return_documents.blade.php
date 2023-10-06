<table id="scrollable_table_admission_documents" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class=""></th>
            <th class="">Level</th>
            <th class="">Program</th>
            <th class="">Classification</th>
            <th class="">Document Name</th>
            <th class="">Display</th>
            <th class="">Required</th>
        </tr>
    </thead>
    <tbody>
        @if ($documents !== null && count($documents) > 0)
            @foreach ($documents as $document)
                <tr class="label">
                    <td class="mid">
                        <input type="checkbox" name="document_id[]" value="{{ $document->id }}" class="checks admission_document" id="check_{{ $document->id }}" />
                    </td>
                    <td class="">{{ $document->educlevel }}</td>
                    <td class="">{{ $document->program ?? 'ALL PROGRAMS'}}</td>
                    <td class="">{{ $document->classification ?? 'ALL CLASSIFICATION' }}</td>
                    <td class="">{{ $document->description }}</td>
                    <td class="mid">{{ $document->display == 1 ? 'YES' : 'NO' }}</td>
                    <td class="mid">{{ $document->is_required == 1 ? 'YES' : 'NO' }}</td>
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
                <th class="">&nbsp;</th>
            </tr>
        @endif
    </tbody>
</table>
