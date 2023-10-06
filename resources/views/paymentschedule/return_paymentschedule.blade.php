<table id="scrollable_table_paymentschedules" class="table table-sm table-striped table-bordered hover compact" style="width:100%; font-size:14px !important;">
    <thead class="">
        <tr>
            <th class="w30"></th>
            <th class="w150">Level</th>
            <th class="w100">Year</th>
            <th class="">Mode</th>
            <th class="">Description</th>
            <th class="w100">Tuition</th>
            <th class="w120">Miscellaneous</th>
            <th class="w100">Others</th>
            <th class="w100">Type</th>
        </tr>
    </thead>
    <tbody id="">
        @if (count($paymentschedules) > 0)
            @foreach ($paymentschedules as $paymentschedule)
                <tr class="label" id="">
                    <td class="w30 mid">
                        <input type="checkbox" name="payment_schedule_id[]" value="{{ $paymentschedule->id }}" class="checks" id="{{ $paymentschedule->id }}" />
                    </td>
                    <td class="w150">{{ $paymentschedule->educlevel->code }}</td>
                    <td class="mid w100">{{ $paymentschedule->year_level }}</td>
                    <td class="">{{ $paymentschedule->paymentmode->mode }}</td>
                    <td class="">{{ $paymentschedule->description }}</td>
                    <td class="w100 mid">{{ $paymentschedule->tuition }}</td>
                    <td class="w120 mid">{{ $paymentschedule->miscellaneous }}</td>
                    <td class="w100 mid">{{ $paymentschedule->others }}</td>
                    <td class="w100">
                        {{ ($paymentschedule->payment_type == 1) ? 'Percentage' : (($paymentschedule->payment_scheme == 2) ? 'Fixed' : '') }}
                    </td>
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
            <th class="">&nbsp;</th>
            <th class="">&nbsp;</th>
        </tr>
        @endif
    </tbody>
</table>