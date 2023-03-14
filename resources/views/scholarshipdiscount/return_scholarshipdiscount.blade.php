<div class="table-responsive" id="table_data">
    <table class="table table-bordered table-striped" id="scholarshipdiscountTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2" class="w40">NO.</th>
                <th rowspan="2" class="w150">Code</th>
                <th rowspan="2" class="">Description</th>
                <th colspan="2" class="w120">Tuition</th>
                <th colspan="2" class="w120">Misc.</th>
                <th colspan="2" class="w120">Other Misc.</th>
                <th colspan="2" class="w120">Laboratory</th>
                <th colspan="2" class="w120">Total Assess</th>
                <th rowspan="2" class="w50"></th>
            </tr>
            <tr>
                <th class="w50">Value</th>
                <th class="w50">Type</th>
                <th class="w50">Value</th>
                <th class="w50">Type</th>
                <th class="w50">Value</th>
                <th class="w50">Type</th>
                <th class="w50">Value</th>
                <th class="w50">Type</th>
                <th class="w50">Value</th>
                <th class="w50">Type</th>
            </tr>
        </thead>
        <tbody>
            @if (count($scholarshipdiscounts) > 0)
                @foreach ($scholarshipdiscounts as $scholarshipdiscount)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $scholarshipdiscount->code }}</td>
                        <td>{{ $scholarshipdiscount->description }}</td>
                        <td>{{ $scholarshipdiscount->tuition }}</td>
                        <td>{{ $scholarshipdiscount->tuition_type }}</td>
                        <td>{{ $scholarshipdiscount->miscellaneous }}</td>
                        <td>{{ $scholarshipdiscount->miscellaneous_type }}</td>
                        <td>{{ $scholarshipdiscount->othermisc }}</td>
                        <td>{{ $scholarshipdiscount->othermisc_type }}</td>
                        <td>{{ $scholarshipdiscount->laboratory }}</td>
                        <td>{{ $scholarshipdiscount->laboratory_type }}</td>
                        <td>{{ $scholarshipdiscount->totalassessment }}</td>
                        <td>{{ $scholarshipdiscount->totalassessment_type }}</td>
                        <td class="mid">
                            <a href="{{ route('scholarshipdiscounts.edit', ['scholarshipdiscount' => $scholarshipdiscount->id ]) }}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text">Edit</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="14" class="mid">No records to be displayed!</td></tr>
            @endif
        </tbody>
    </table>
    @if(
        $scholarshipdiscounts instanceof \Illuminate\Pagination\Paginator ||
        $scholarshipdiscounts instanceof \Illuminate\Pagination\LengthAwarePaginator
        )
        {{ $scholarshipdiscounts->onEachSide(1)->links() }}
        Showing {{ $scholarshipdiscounts->firstItem() }} to {{ $scholarshipdiscounts->lastItem() }} of total {{$scholarshipdiscounts->total()}} entries
    @endif
</div>