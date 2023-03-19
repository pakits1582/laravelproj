@if (count($scholarshipdiscountgrants) > 0)
    @foreach ($scholarshipdiscountgrants as $scholarshipdiscountgrant)
        <tr>
            <td class="w170">Code</td>
            <td class="">Description</td>
            <td class="w120">Tuition</td>
            <td class="w120">Miscellaneous</td>
            <td class="w120">Otder Misc.</td>
            <td class="w120">Laboratory</td>
            <td class="w120">Total Assessment</td>
            <td class="w120">Total Deduction</td>
            <td class="w50">Action</td>
        </tr>
    @endforeach
@else
    <tr><td class="mid" colspan="9">No records to be displayed!</td></tr>
@endif