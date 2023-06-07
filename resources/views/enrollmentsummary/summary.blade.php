@if (count($enrollment_summary) > 0)
    <table class="table table-sm table-striped table-bordered" id="enrollment_summary_table">
        <tbody id="">
            @php
                 $totalreserved = 0;
                $totalvalidated = 0;
                $grandtotal = 0;
                $grandtotaltyear1 = 0;
                $grandtotaltyear2 = 0;
                $grandtotaltyear3 = 0;
                $grandtotaltyear4 = 0;
                $grandtotaltyear5 = 0;
                $grandtotaltyear6 = 0;

                $grandtotalyearres1 = 0;
                $grandtotalyearres2 = 0;
                $grandtotalyearres3 = 0;
                $grandtotalyearres4 = 0;
                $grandtotalyearres5 = 0;
                $grandtotalyearres6 = 0;

                $grandtotalyearcon1 = 0;
                $grandtotalyearcon2 = 0;
                $grandtotalyearcon3 = 0;
                $grandtotalyearcon4 = 0;
                $grandtotalyearcon5 = 0;
                $grandtotalyearcon6 = 0;
            @endphp
            @foreach ($enrollment_summary as $value)
                <tr>
                    <th class="left primary-color">{{ $value['college_code'] }}</th>
                    <th colspan="3" class="w150 primary-color">1st Year</th>
                    <th colspan="3" class="w150 primary-color">2nd Year</th>
                    <th colspan="3" class="w150 primary-color">3rd Year</th>
                    <th colspan="3" class="w150 primary-color">4th Year</th>
                    <th colspan="3" class="w150 primary-color">5th Year</th>
                    <th colspan="3" class="w150 primary-color">Total</th>
                </tr>
                <tr>
                    <th class="">PROGRAMS</th>
                    <th class="w50">Res</th>
                    <th class="w50">Con</th>
                    <th class="w50">Total</th>
                    <th class="w50">Res</th>
                    <th class="w50">Con</th>
                    <th class="w50">Total</th>
                    <th class="w50">Res</th>
                    <th class="w50">Con</th>
                    <th class="w50">Total</th>
                    <th class="w50">Res</th>
                    <th class="w50">Con</th>
                    <th class="w50">Total</th>
                    <th class="w50">Res</th>
                    <th class="w50">Con</th>
                    <th class="w50">Total</th>
                    <th class="w50">Res</th>
                    <th class="w50">Con</th>
                    <th class="w50">Total</th>
                </tr>
                @php
                    $subtotalreserved_course = 0;
                    $subtotalvalidated_course = 0;
                    $subtotal_course = 0;
                    $totalyearres1 = 0;
                    $totalyearres2 = 0;
                    $totalyearres3 = 0;
                    $totalyearres4 = 0;
                    $totalyearres5 = 0;
                    $totalyearres6 = 0;

                    $totalyearcon1 = 0;
                    $totalyearcon2 = 0;
                    $totalyearcon3 = 0;
                    $totalyearcon4 = 0;
                    $totalyearcon5 = 0;
                    $totalyearcon6 = 0;

                    $totaltyear1 = 0;
                    $totaltyear2 = 0;
                    $totaltyear3 = 0;
                    $totaltyear4 = 0;
                    $totaltyear5 = 0;
                    $totaltyear6 = 0;
                @endphp
                @if ($value['programs'])
                    @foreach ($value['programs'] as $v)
                        @php
                            $subtotalreserved = 0;
                            $subtotalvalidated = 0;
                        @endphp
                        <tr>
                            <td class="font-weight-bold">{{ $v['program_code'] }}</td>
                            @php
                                $totalperyear = 0;
                            @endphp
                            @for ($i=1; $i <= count($v['year_level']) ; $i++)
                                @php
                                    $enrolledreserved = $v['year_level'][$i]['res'];
                                    $enrolledvalidated = $v['year_level'][$i]['con'];
                                @endphp
                                <td class="mid">{{ $enrolledreserved }}</td>
                                <td class="mid">{{ $enrolledvalidated }}</td>
                                @php
                                    $subtotalreserved += $enrolledreserved;
                                    $subtotalvalidated += $enrolledvalidated;
                                    $totalperyear = $enrolledreserved+$enrolledvalidated;
                                @endphp
                                <td class="mid tutorial">{{ $totalperyear }}</td>
                                @php
                                    ${'totaltyear'.$i} += $totalperyear;
                                    ${'totalyearres'.$i} += $enrolledreserved;
                                    ${'totalyearcon'.$i} += $enrolledvalidated;
                                @endphp
                            @endfor
                            <td class="mid">{{ $subtotalreserved }}</td>
                            <td class="mid">{{ $subtotalvalidated }}</td>
                            @php
                                $subtotal = $subtotalreserved+$subtotalvalidated;
                            @endphp
                            <td class="mid">{{ $subtotal }}</td>
                        </tr>
                        @php
                            $totalreserved += $subtotalreserved;
                            $totalvalidated += $subtotalvalidated;

                            $subtotalreserved_course += $subtotalreserved;
                            $subtotalvalidated_course += $subtotalvalidated;
                            $subtotal_course += $subtotal;
                        @endphp
                    @endforeach
                @else
                    <tr class="nohover mid"><td colspan="22" class="">
                        <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
                    </tr>
                @endif
                <tr class="nohover">
                    <th class="right">Total</th>
                    <th class="mid dissolved">{{ $totalyearres1 }}</th>
                    <th class="mid tutorial">{{ $totalyearcon1 }}</th>
                    <th class="mid blued">{{ $totaltyear1 }}</th>
                    <th class="mid dissolved">{{ $totalyearres2 }}</th>
                    <th class="mid tutorial">{{ $totalyearcon2 }}</th>
                    <th class="mid blued">{{ $totaltyear2 }}</th>
                    <th class="mid dissolved">{{ $totalyearres3 }}</th>
                    <th class="mid tutorial">{{ $totalyearcon3 }}</th>
                    <th class="mid blued">{{ $totaltyear3 }}</th>
                    <th class="mid dissolved">{{ $totalyearres4 }}</th>
                    <th class="mid tutorial">{{ $totalyearcon4 }}</th>
                    <th class="mid blued">{{ $totaltyear4 }}</th>
                    <th class="mid dissolved">{{ $totalyearres5 }}</th>
                    <th class="mid tutorial">{{ $totalyearcon5 }}</th>
                    <th class="mid blued">{{ $totaltyear5 }}</th>
                    <th class="mid dissolved">{{ $subtotalreserved_course }}</th>
                    <th class="mid tutorial">{{ $subtotalvalidated_course }}</th>
                    <th class="mid blued">{{ $subtotal_course }}</th>
                </tr>
                @php
                    $grandtotaltyear1 += $totaltyear1;
                    $grandtotaltyear2 += $totaltyear2;
                    $grandtotaltyear3 += $totaltyear3;
                    $grandtotaltyear4 += $totaltyear4;
                    $grandtotaltyear5 += $totaltyear5;
                    $grandtotaltyear6 += $totaltyear6;

                    $grandtotalyearres1 += $totalyearres1;
                    $grandtotalyearres2 += $totalyearres2;
                    $grandtotalyearres3 += $totalyearres3;
                    $grandtotalyearres4 += $totalyearres4;
                    $grandtotalyearres5 += $totalyearres5;
                    $grandtotalyearres6 += $totalyearres6;

                    $grandtotalyearcon1 += $totalyearcon1;
                    $grandtotalyearcon2 += $totalyearcon2;
                    $grandtotalyearcon3 += $totalyearcon3;
                    $grandtotalyearcon4 += $totalyearcon4;
                    $grandtotalyearcon5 += $totalyearcon5;
                    $grandtotalyearcon6 += $totalyearcon6;
                @endphp
                <tr class="nohover"><td colspan="22">&nbsp;</td></tr>
            @endforeach
            @php
                $grandtotal = $totalreserved+$totalvalidated;
            @endphp
            <tr>
                <th class=" primary-color">GRAND TOTAL</th>
                <th colspan="3" class="w120 primary-color">1st Year</th>
                <th colspan="3" class="w120 primary-color">2nd Year</th>
                <th colspan="3" class="w120 primary-color">3rd Year</th>
                <th colspan="3" class="w120 primary-color">4th Year</th>
                <th colspan="3" class="w120 primary-color">5th Year</th>
                <th colspan="3" class="w180 primary-color">Total</th>
            </tr>
            <tr>
                <th class=""></th>
                <th class="w50">Res</th>
                <th class="w50">Con</th>
                <th class="w50">Total</th>
                <th class="w50">Res</th>
                <th class="w50">Con</th>
                <th class="w50">Total</th>
                <th class="w50">Res</th>
                <th class="w50">Con</th>
                <th class="w50">Total</th>
                <th class="w50">Res</th>
                <th class="w50">Con</th>
                <th class="w50">Total</th>
                <th class="w50">Res</th>
                <th class="w50">Con</th>
                <th class="w50">Total</th>
                <th class="w50">Res</th>
                <th class="w50">Con</th>
                <th class="w50">Total</th>
            </tr>
            <tr class="nohover">
                <th class="right"></th>
                <th class="mid dissolved">{{ $grandtotalyearres1 }}</th>
                <th class="mid tutorial">{{ $grandtotalyearcon1 }}</th>
                <th class="mid blued">{{ $grandtotaltyear1 }}</th>
                <th class="mid dissolved">{{ $grandtotalyearres2 }}</th>
                <th class="mid tutorial">{{ $grandtotalyearcon2 }}</th>
                <th class="mid blued">{{ $grandtotaltyear2 }}</th>
                <th class="mid dissolved">{{ $grandtotalyearres3 }}</th>
                <th class="mid tutorial">{{ $grandtotalyearcon3 }}</th>
                <th class="mid blued">{{ $grandtotaltyear3 }}</th>
                <th class="mid dissolved">{{ $grandtotalyearres4 }}</th>
                <th class="mid tutorial">{{ $grandtotalyearcon4 }}</th>
                <th class="mid blued">{{ $grandtotaltyear4 }}</th>
                <th class="mid dissolved">{{ $grandtotalyearres5 }}</th>
                <th class="mid tutorial">{{ $grandtotalyearcon5 }}</th>
                <th class="mid blued">{{ $grandtotaltyear5 }}</th>
                <th class="mid dissolved">{{ $totalreserved }}</th>
                <th class="mid tutorial">{{ $totalvalidated }}</th>
                <th class="mid blued">{{ $grandtotal }}</th>
            </tr>
            <tr class="nohover">
                <td colspan="22">
                    <div class="notes font-weight-bold"><span>Note: </span>Enrollment summary as of {{ \Carbon\Carbon::now()->format('F d, Y') }}.</div>
                </td>
            </tr>
        </tbody>
    </table>
@else
    <h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>
@endif