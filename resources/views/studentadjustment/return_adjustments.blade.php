@if (count($studentadjustments) > 0)
    @foreach ($studentadjustments as $studentadjustment)
        <tr>
            <td class="w200">{{ \Carbon\Carbon::parse($studentadjustment->created_at)->format('F d, Y') }}</td>
            <td class="w150">
                @switch($studentadjustment->type)
                    @case(1)
                        CREDIT
                        @break
                    @case(2)
                        DEBIT
                        @break
                    @case(3)
                        REFUND
                        @break
                    @default
                @endswitch
            </td>
            <td class="">{{ $studentadjustment->particular }}</td>
            <td class="w150 right">{{ number_format($studentadjustment->amount,2) }}</td>
            <td class="w100 mid">
                <a href="#" id="{{ $studentadjustment->id }}" class="delete_adjustment btn btn-danger btn-circle btn-sm" title="Delete">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="5">No records to be displayed!</td></tr>
@endif