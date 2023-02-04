@if (count($feessetups) > 0)
    @foreach ($feessetups as $feessetup)
        <tr class="label" id="check">
            <td class="w20"><input type="checkbox" data-setupfeeid="{{ $feessetup->id }}" class="checks" id="check_{{ $feessetup->id }}" /></td>
            <td class="w100">{{ $feessetup->educlevel->code }}</td>
            <td class="w100">{{ $feessetup->college->code }}</td>
            <td class="w100">{{ $feessetup->program->code }}</td>
            <td class="mid w40">{{ $feessetup->year_level }}</td>
            <td class="mid w40">{{ ($feessetup->new === 0) ? '' : 'Y' }}</td>
            <td class="mid w40">{{ ($feessetup->old === 0) ? '' : 'Y' }}</td>
            <td class="mid w40">{{ ($feessetup->sex) ? (($feessetup->sex === 1) ? 'M' : 'F') : '' }}</td>
            <td class="mid w40">{{ ($feessetup->transferee === 0) ? '' : 'Y' }}</td>
            <td class="mid w40">{{ ($feessetup->cross_enrollee === 0) ? '' : 'Y' }}</td>
            <td class="mid w40">{{ ($feessetup->professional === 0) ? '' : 'Y' }}</td>
            <td class="w150">{{ $feessetup->subject->code }}</td>
            <td>{{ $feessetup->fee->code }}</td>
            <td>{{ $feessetup->fee->feetype->type }}</td>
            <td class="w70">{{ $feessetup->rate }}</td>
            <td class="w70">
                {{ ($feessetup->payment_scheme == 1) ? 'Fixed' : (($feessetup->payment_scheme == 2) ? 'Units' : 'Subject') }}
            </td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="16">No records to be displayed!</td></tr>
@endif