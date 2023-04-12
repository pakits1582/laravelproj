@if (count($scholarshipdiscountgrants) > 0)
    @foreach ($scholarshipdiscountgrants as $scholarshipdiscountgrant)
        <tr>
            <td class="w170">{{ $scholarshipdiscountgrant->scholarshipdiscount->code }}</td>
            <td class="">{{ $scholarshipdiscountgrant->scholarshipdiscount->description }}</td>
            <td class="w120 right">{{ number_format($scholarshipdiscountgrant->tuition,2) }}</td>
            <td class="w120 right">{{ number_format($scholarshipdiscountgrant->miscellaneous,2) }}</td>
            <td class="w120 right">{{ number_format($scholarshipdiscountgrant->othermisc,2) }}</td>
            <td class="w120 right">{{ number_format($scholarshipdiscountgrant->laboratory,2) }}</td>
            <td class="w120 right">{{ number_format($scholarshipdiscountgrant->totalassessment,2) }}</td>
            <td class="w120 right">{{ number_format($scholarshipdiscountgrant->totaldeduction,2) }}</td>
            <td class="w50 mid">
                <a href="#" id="{{ $scholarshipdiscountgrant->id }}" class="delete_grant btn btn-danger btn-circle btn-sm" title="Delete">
                    <i class="fas fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="9">No records to be displayed!</td></tr>
@endif