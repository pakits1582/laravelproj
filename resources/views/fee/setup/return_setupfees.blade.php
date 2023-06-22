<table id="scrollable_table" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead>
        <tr>
            <th class=""></th>
            <th class="">Level</th>
            <th class="">College</th>
            <th class="">Program</th>
            <th class="">Yr.</th>
            <th class="">Nw</th>
            <th class="">Old</th>
            <th class="">Sex</th>
            <th class="">Crs</th>
            <th class="">Trns</th>
            <th class="">PS</th>
            <th class="">Subject</th>
            <th class="">Fee</th>
            <th class="">Fee Type</th>
            <th class="">Rate</th>
            <th class="">Schm</th>
        </tr>
    </thead>
    <tbody>
        @if (count($feessetups) > 0)
            @foreach ($feessetups as $feessetup)
                <tr class="label" id="">
                    <td class="w30 mid">
                        <input type="checkbox" name="setup_fee_id[]" value="{{ $feessetup->id }}" data-setupfeeid="{{ $feessetup->id }}" class="{{ ($selectall == 0) ? 'checks' : 'copyfees_checkbox' }}" id="check_{{ $feessetup->id }}" />
                    </td>
                    <td class="">{{ $feessetup->educlevel->code }}</td>
                    <td class="">{{ $feessetup->college->code }}</td>
                    <td class="">{{ $feessetup->program->code }}</td>
                    <td class="mid ">{{ $feessetup->year_level }}</td>
                    <td class="mid ">{{ ($feessetup->new === 0) ? '' : 'Y' }}</td>
                    <td class="mid ">{{ ($feessetup->old === 0) ? '' : 'Y' }}</td>
                    <td class="mid ">{{ ($feessetup->sex) ? (($feessetup->sex === 1) ? 'M' : 'F') : '' }}</td>
                    <td class="mid ">{{ ($feessetup->transferee === 0) ? '' : 'Y' }}</td>
                    <td class="mid ">{{ ($feessetup->cross_enrollee === 0) ? '' : 'Y' }}</td>
                    <td class="mid ">{{ ($feessetup->professional === 0) ? '' : 'Y' }}</td>
                    <td class="">{{ $feessetup->subject->code }}</td>
                    <td class="">{{ $feessetup->fee->name }}</td>
                    <td class="">{{ $feessetup->fee->feetype->type }}</td>
                    <td class="">{{ $feessetup->rate }}</td>
                    <td class="">
                        {{ ($feessetup->payment_scheme == 1) ? 'Fixed' : (($feessetup->payment_scheme == 2) ? 'Units' : 'Subject') }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
                <td class="">&nbsp;</td>
            </tr>
        @endif
    </tbody>
</table>