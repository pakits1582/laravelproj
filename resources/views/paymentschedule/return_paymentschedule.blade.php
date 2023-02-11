@if (count($paymentschedules) > 0)
    @foreach ($paymentschedules as $paymentschedule)
        <tr class="label" id="">
            <td class="w30 mid">
                <input type="checkbox" name="setup_fee_id[]" value="{{ $paymentschedule->id }}" class="checks" id="check_{{ $paymentschedule->id }}" />
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
    <tr><td class="mid" colspan="8">No records to be displayed!</td></tr>
@endif