<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assessment</title>
    <style>
        @page{
          margin-top: 30px;
        }
        header{
          position: fixed;
          left: 0px;
          right: 0px;
          height: 60px;
          margin-top: -60px;
        }

        @font-face {
            font-family: 'centurygothic';
            src: url({{ storage_path("fonts/century_gothic.ttf") }}) format("truetype");
            font-style: normal;
        }

        @font-face {
            font-family: 'centurygothicb';
            src: url({{ storage_path("fonts/gothicb.ttf") }}) format("truetype");
            font-style: bold;
        }

        *{
            font-family:centurygothic !important;
            word-wrap:break-word !important;
            font-size:14px;
        }

        h1, h2, h3, h4, h5, h6, .bold, label, span {
            font-family:centurygothicb !important;
        }

        .w10{
            width:10px !important;	
        }
        .w15{
            width:15px !important;	
        }
        .w20{
            width:25px !important;	
        }
        .w30{
            width:30px !important;	
        }
        .w35{
            width:35px !important;	
        }
        .w40{
            width:40px !important;	
        }
        .w49{
            width:48px !important;	
        }
        .w50{
            width:50px !important;	
        }
        .w60{
            width:60px !important;	
        }
        .w70{
            width:70px !important;	
        }
        .w80{
            width:80px !important;	
        }
        .w85{
            width:85px !important;	
        }
        .w100{
            width:100px !important;	
        }
        .w90{
            width:90px !important;	
        }
        .w120{
            width:120px !important;	
        }
        .w150{
            width:150px !important;	
        }
        .w170{
            width:170px !important;	
        }
        .w180{
            width:180px !important;	
        }
        .wt190{
            width:190px !important;	
        }
        .w190{
            width:193px !important;	
        }
        .w200{
            width:200px !important;	
        }
        .w220{
            width:220px !important;	
        }
        .w250{
            width:250px !important;	
        }
        .w275{
            width:275px !important;	
        }
        .w300{
            width:300px !important;	
        }
        .w350{
            width:350px !important;	
        }
        .w380{
            width:380px !important;	
        }
        .w400{
            width:450px !important;	
        }
        .w450{
            width:450px !important;	
        }

        .w500{
            width:500px !important;	
        }
        .w550{
            width:550px !important;	
        }
        .w600{
            width:600px !important;	
        }
        .w650{
            width:650px !important;	
        }
        .w700{
            width:712px !important;	
        }
        .w850{
            width:850px !important;	
        }

        .col10{display:inline-block;width:10%;}
        .col15{display:inline-block;width:15%;}
        .col20{display:inline-block;width:19%;}
        .col25{display:inline-block;width:24%;}
        .col30{display:inline-block;width:29%;}
        .col33{display:inline-block;width:33%;}
        .col35{display:inline-block;width:35%;}
        .col40{display:inline-block;width:39%;}
        .col50{display:inline-block;width:49%;}
        .col60{display:inline-block;width:59%;}
        .col70{display:inline-block;width:69%;}
        .col75{display:inline-block;width:74%;}
        .col80{display:inline-block;width:79%;}
        .col90{display:inline-block;width:89%;}
        .col100{display:inline-block;width:99%;}


        .bold, label{
            font-weight: 700;
            font-size: 12px !important;
        }

        .mid{
            text-align: center;
        }

        .right{
            text-align: right;
        }

        .nomargin{
            margin: 0px !important;
        }

        .trimmargin{
            margin: 3px !important;
        }

        /******* TABLE ASSESS *********/
        table.assess_table{
            border-collapse:collapse;
            clear:both;
            width:100%;
        }
        table.assess_table  th{
           font-size: 12px !important;
           font-family: centurygothicb !important;
           border:1px solid #ccc !important;
        }
        table.assess_table tr:nth-child(odd) {
            background-color:#f2f2f2;
        }
        table.assess_table td {
            border:1px solid #ccc !important;
            font-size: 10px !important;
            padding-right: 3px;
            padding-left: 3px;
            line-height: 10px;
        }

        table.heading{
            border-collapse:collapse;
            clear:both;
            width:100%;
            margin:10px 0px;
        }
        table.heading  th{
           font-size: 12px !important;
           font-family: centurygothicb !important;
        }
        table.heading tr:nth-child(odd) {
            /* background-color:#f2f2f2; */
        }
        table.heading td{
            border-collapse:collapse;
            /* border:1px solid #ccc !important; */
            /* padding:3px !important; */
            margin:0px;
            padding: 0px;
            font-size: 12px !important;
            line-height: 10px;
        }

        table.tablefees{
            border-collapse:collapse;
            border-spacing: 0;
        }
        table.tablefees td{
            border-collapse:collapse;
            font-size: 10px !important;
            padding: 0px 3px;
            margin: -3 !important!;
            border-spacing: 0;
            line-height: 10px;
            /* border:1px solid #ccc !important; */
        }
        
        .text-black, span{
            font-size: 10px;
        }

        hr{
            color: #ccc;
            margin: 3px;
        }
      </style>
</head>
<body>
    <div>
        <!-- Page Heading -->
        @php
            $professional_subjects = $enrolled_classes->where('class.curriculumsubject.subjectinfo.professional', 1)->sum('class.tfunits');
            $academic_subjects = $enrolled_classes->where('class.curriculumsubject.subjectinfo.professional', 0)->sum('class.tfunits');
            $total_subjects = $enrolled_classes->count();
            $total_units = $enrolled_classes->sum('class.tfunits');
            $laboratory_subjects  = [];
            $all_subjects  = [];
    
        @endphp
   
        <div class="">
            <div class="bold mid">{{ $configuration->name }}</div>
            <div class="bold mid">{{ $configuration->address }}</div>
            <div class="bold mid">Assessment ({{ $assessment->enrollment->period->name }})</div>
        </div>
        <table class="heading" style="width: 100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td class="w120"><label>Enroll No. & Date</label></td>
                <td><label>:</label> {{ $assessment->enrollment->id }} - {{ date("F d, Y H:i A", strtotime($assessment->enrollment->created_at)) }}</td>
                <td class="w100"><label>Assessment No.</label></td>
                <td><label>:</label> {{ $assessment->id }}</td>
            </tr>
            <tr>
                <td><label>ID Number</label></td>
                <td><label>:</label> {{ $assessment->enrollment->student->user->idno }}</td>
                <td><label>Program & Year</label></td>
                <td><label>:</label> {{ $assessment->enrollment->program->code }}-{{ $assessment->enrollment->year_level }}</td>
            </tr>
            <tr>
                <td><label>Student Name</label></td>
                <td><label>:</label> {{ $assessment->enrollment->student->last_name }}, {{ $assessment->enrollment->student->first_name }} {{ $assessment->enrollment->student->name_suffix }} {{ $assessment->enrollment->student->middle_name }}</td>
                <td><label>Section</label></td>
                <td><label>:</label> {{ $assessment->enrollment->section->code }}</td>
            </tr>
        </table>
        <table class="assess_table">
            <thead class="">
                <tr>
                    <th>Code</th>
                    <th>Subject</th>
                    <th class="">Description</th>
                    <th class="">Units</th>
                    <th class="">Lec</th>
                    <th class="">Lab</th>
                    <th class="">Schedule</th>
                    <th class="">Section</th>
                </tr>
            </thead>
            <tbody class="" id="">
                @if (count($enrolled_classes) > 0)
                    @php
                        $totalunits = 0;
                    @endphp
                    @foreach ($enrolled_classes as $enrolled_class)
                        @php
                            $row_color = '';
                            if($enrolled_class->class->dissolved === 1)
                            {
                                $row_color = 'dissolved';
                            }elseif ($enrolled_class->class->tutorial === 1) {
                                $row_color = 'tutorial';
                            }elseif ($enrolled_class->class->f2f === 1) {
                                $row_color = 'f2f';
                            }
                        @endphp
                        <tr class="label {{ $row_color }}">
                            <td class="">{{ $enrolled_class->class->code }}</td>
                            <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->code }}</td>
                            <td class="">{{ $enrolled_class->class->curriculumsubject->subjectinfo->name }}</td>
                            <td class="mid">{{ ($enrolled_class->class->isprof === 1) ? '('.$enrolled_class->class->units.')' : $enrolled_class->class->units }}</td>
                            <td class="mid">{{ $enrolled_class->class->lecunits }}</td>
                            <td class="mid">{{ $enrolled_class->class->labunits }}</td>
                            <td class="">{{ $enrolled_class->class->schedule->schedule }}</td>
                            <td class="">{{ $enrolled_class->class->sectioninfo->code }}</td>
                        </tr>
                        @php
                            $totalunits += $enrolled_class->class->units;
                            $all_subjects[] = $enrolled_class->class->curriculumsubject->subjectinfo->id;

                            if($enrolled_class->class->curriculumsubject->subjectinfo->laboratory == 1)
                            {
                                $laboratory_subjects[] = $enrolled_class->class->curriculumsubject->subjectinfo->id;
                            }
                        @endphp
                    @endforeach
                    <tr class="nohover">
                        <td colspan="3" class="bold">Total Subjects ({{ count($enrolled_classes) }})</td>
                        <td colspan="5" class="bold">(<span id="enrolledunits">{{ $totalunits }}</span>) Total Units</td>
                    </tr>
                @else
                    <tr class="">
                        <td class="mid" colspan="8">No records to be displayed</td>
                    </tr>
                @endif
            </tbody>
        </table>
    
        @php
            $idno            = $assessment->enrollment->student->user->idno;
            $userid          = $assessment->enrollment->student->user->id;
            $enrollment_id   = $assessment->enrollment->id;
            $assessment_id   = $assessment->id;
            $educational_level_id  = $assessment->enrollment->program->educational_level_id;
            $college_id      = $assessment->enrollment->program->college_id;
            $program_id      = $assessment->enrollment->program->id;
            $year_level      = $assessment->enrollment->year_level;
            $new             = $assessment->enrollment->new;
            $old             = $assessment->enrollment->old;
            $transferee      = $assessment->enrollment->transferee;
            $cross_enrollee  = $assessment->enrollment->cross_enrollee;
            $foreigner       = $assessment->enrollment->foreigner;
            $returnee        = $assessment->enrollment->returnee;
            $sex             = $assessment->enrollment->student->sex;
            $enrollment_date = $assessment->enrollment->created_at;
            $validated       = $assessment->enrollment->validated;
            $feesArray = [];
    
            if(count($setup_fees) > 0)
            {
                foreach ($setup_fees->toArray() as $key => $fee) 
                {
                    $passed = 1;  
                    
                    if($fee['educational_level_id'] != NULL && $passed == 1) {
                        $passed = ($educational_level_id == $fee['educational_level_id']) ? 1 : 0;
                    }
    
                    if($fee['college_id'] != NULL && $passed == 1){
                        $passed = ($college_id == $fee['college_id']) ? 1 : 0;
                    }
    
                    if($fee['program_id'] != NULL && $passed == 1){
                        $passed = ($program_id == $fee['program_id']) ? 1 : 0;
                    }
    
                    if($fee['year_level'] != NULL && $passed == 1){
                        $passed = ($year_level == $fee['year_level']) ? 1 : 0;
                    }
    
                    if($fee['new'] != 0 && $passed == 1){
                        $passed = ($new == $fee['new']) ? 1 : 0;
                    }
    
                    if($fee['old'] != 0 && $passed == 1){
                        $passed = ($old == $fee['old']) ? 1 : 0;
                    }
    
                    if($fee['transferee'] != 0 && $passed == 1){
                        $passed = ($transferee == $fee['transferee']) ? 1 : 0;
                    }
    
                    if($fee['cross_enrollee'] != 0 && $passed == 1){
                        $passed = ($cross_enrollee == $fee['cross_enrollee']) ? 1 : 0;
                    }
    
                    if($fee['foreigner'] != 0 && $passed == 1){
                        $passed = ($foreigner == $fee['foreigner']) ? 1 : 0;
                    }
    
                    if($fee['returnee'] != 0 && $passed == 1){
                        $passed = ($returnee == $fee['returnee']) ? 1 : 0;
                    }
    
                    if($fee['sex'] != NULL && $passed == 1){
                        $passed = ($sex == $fee['sex']) ? 1 : 0;
                    }
    
                    if($fee['subject_id'] != NULL){
                        $passed = in_array($fee['subject_id'], $laboratory_subjects) ? 1 : 0;
                    }
    
                    if($fee['subject_id'] != NULL){
                        $passed = in_array($fee['subject_id'], $all_subjects) ? 1 : 0;
                    }
    
                    if($passed == 1)
                    {
                        $feesArray[] = $fee;
                    }
                }
            }
    
            $uniqueFeeTypes = collect(array_values(array_unique(array_column(array_column($feesArray, 'fee'), 'feetype'), SORT_REGULAR)))->sortBy('order');
            $totaltuition = 0;
            $labfeetotal = 0;
            $miscfeetotal = 0;
            $otherfeetotal = 0;
            $additionalfeetotal = 0;
            $totalfees = 0;
        @endphp

        <div class="row">
            <h6 class="bold text-black trimmargin">ASSESSMENT OF FEES</h6> 
        </div>

        <table style="width: 100%;">
            <tr>
                <td style="width: 50% !important;">
                    @if ($uniqueFeeTypes)
                        @foreach ($uniqueFeeTypes->toArray() as $key => $feetype)
                            <h6 class="bold text-black nomargin">{{ $feetype['type'] }}</h6>
                            <table class="tablefees" style="width: 100%;" cellpadding="0" cellspacing="0">
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($feesArray as $k => $fee)
                                    {{-- {{ print_r($fee) }} --}}
                                    @if ($fee['fee']['fee_type_id'] == $feetype['id'])
                                        @php
                                            if(strcasecmp($feetype['type'], 'Tuition Fees') == 0)
                                            {
                                                if(strcasecmp($fee['fee']['name'], 'academic') == 0)
                                                {
                                                    if($academic_subjects != 0)
                                                    {
                                                        switch ($fee['payment_scheme']) {
                                                            case 1: //fixed
                                                                $acadsubjtotal = $fee['rate'];
                                                                $description   = $fee['fee']['name'].' (Fixed : '.$fee['rate'].')';
                                                                break;
                                                            case 2: //per units
                                                                $acadsubjtotal = $academic_subjects*$fee['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$academic_subjects.' unit(s) x '.$fee['rate'].'/unit)';
                                                                break;
                                                            case 3: //per subject
                                                                $acadsubjtotal = $total_subjects*$v['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$total_subjects.' subject(s) x '.$fee['rate'].'/subject)';
                                                                break;
                                                            default:
                                                                $acadsubjtotal = $fee['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$fee['rate'].')';
                                                                break;
                                                        }

                                                    @endphp   
                                                        <tr>
                                                            <td class="">{{ $description }}</td>
                                                            <td class="right w70">{{ number_format($acadsubjtotal,2) }}</td>                                                        </tr>
                                                        @php
                                                            $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $acadsubjtotal);
                                                            $total += $acadsubjtotal;
                                                            $totaltuition += $acadsubjtotal;
                                                        @endphp
                                                    @php
                                                    }
                                                }
                                                //PROFESSIONAL
                                                if(strcasecmp($fee['fee']['name'], 'professional') == 0)
                                                {
                                                    if($professional_subjects != 0)
                                                    {
                                                        switch ($fee['payment_scheme']) {
                                                            case 1: //fixed
                                                                $profsubjtotal = $fee['rate'];
                                                                $description   = $fee['fee']['name'].' (Fixed : '.$fee['rate'].')';
                                                                break;
                                                            case 2: //per units
                                                                $profsubjtotal = $professional_subjects*$fee['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$professional_subjects.' unit(s) x '.$fee['rate'].'/unit)';
                                                                break;
                                                            case 3: //per subject
                                                                $profsubjtotal = $total_subjects*$v['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$total_subjects.' subject(s) x '.$fee['rate'].'/subject)';
                                                                break;
                                                            default:
                                                                $profsubjtotal = $fee['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$fee['rate'].')';
                                                                break;
                                                        }

                                                    @endphp   
                                                        <tr>
                                                            <td class="">{{ $description }}</td>
                                                            <td class="right w70">{{ number_format($profsubjtotal,2) }}</td>
                                                        </tr>
                                                        @php
                                                            $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $profsubjtotal);
                                                            $total += $profsubjtotal;
                                                            $totaltuition += $profsubjtotal;
                                                        @endphp
                                                    @php
                                                    }
                                                }
                                                //TUITION FEE
                                                if(strcasecmp($fee['fee']['name'], 'tuition fee') == 0)
                                                {
                                                    if($otalunits != 0)
                                                    {
                                                        switch ($fee['payment_scheme']) {
                                                            case 1: //fixed
                                                                $tuitiontotal = $fee['rate'];
                                                                $description   = $fee['fee']['name'].' (Fixed : '.$fee['rate'].')';
                                                                break;
                                                            case 2: //per units
                                                                $tuitiontotal = $total_units*$fee['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$total_units.' unit(s) x '.$fee['rate'].'/unit)';
                                                                break;
                                                            case 3: //per subject
                                                                $tuitiontotal = $total_subjects*$v['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$total_subjects.' subject(s) x '.$fee['rate'].'/subject)';
                                                                break;
                                                            default:
                                                                $tuitiontotal = $fee['rate'];
                                                                $description   = $fee['fee']['name'].' ('.$fee['rate'].')';
                                                                break;
                                                        }

                                                    @endphp   
                                                        <tr>
                                                            <td class="">{{ $description }}</td>
                                                            <td class="right w70">{{ number_format($tuitiontotal,2) }}</td>
                                                        </tr>
                                                        @php
                                                            $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $tuitiontotal);
                                                            $total += $tuitiontotal;
                                                            $totaltuition += $tuitiontotal;
                                                        @endphp
                                                    @php
                                                    }
                                                }
                                            }else{
                                                $feesdesc = $fee['fee']['name'];
                                                $rate = 0;

                                                switch ($fee['payment_scheme']) 
                                                {
                                                    case 1: //fixed
                                                        $rate = $fee['rate'];
                                                        break;
                                                    case 2: //per units
                                                        $rate = $total_units*$fee['rate'];
                                                        break;
                                                    case 3: //per subject
                                                        $rate = $total_subjects*$fee['rate'];
                                                        break;
                                                    default:
                                                        $rate = $fee['rate'];
                                                        break;
                                                }
                                                if(strcasecmp($feetype['type'], 'Miscellaneous Fees') == 0) {
                                                    $miscfeetotal += $rate;
                                                }
                                                if(strcasecmp($feetype['type'], 'Other Miscellaneous Fees') == 0) {
                                                    $otherfeetotal += $rate;
                                                }
                                                if(strcasecmp($feetype['type'], 'Additional Fees') == 0) {
                                                    $additionalfeetotal += $rate;
                                                }
                                                if($fee['subject_id'] != 0 && strcasecmp($feetype['type'], 'Laboratory Fees') == 0)
                                                {
                                                    if(in_array($fee['subject_id'], $laboratory_subjects) === true)
                                                    {
                                                        $feesdesc .= '('.$fee['subject']['code'].')';
                                                        $labfeetotal += $rate;
                                                    }
                                                }
                                                @endphp
                                                    <tr>
                                                        <td class="">{{ $feesdesc }}</td>
                                                        <td class="right w70">{{ number_format($rate,2) }}</td>
                                                    </tr>
                                                    @php
                                                        $allfees[] = array('fee' => $fee['fee_id'], 'amount' => $rate);
                                                        $total += $rate;
                                                    @endphp
                                                @php
                                            }
                                        @endphp
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="2"><hr class="trimmargin"></td>
                                </tr>
                                <tr>
                                    <td class="bold">Total {{ $feetype['type'] }}</td>
                                    <td class="bold right w100">{{ number_format($total,2) }}</td>
                                </tr>
                            </table>
                            @php
                                $totalfees += $total;
                            @endphp
                        @endforeach    
                    @endif
                </td>
                <td style="width: 50% !important;" valign="top">
                    <div class="col100">
                        <div class="col70">
                            <h6 class="bold text-black trimmargin">TOTAL TUITION AND FEES</h6> 
                        </div>
                        <div class="col30 right">
                            <h6 class="bold text-black trimmargin">{{ 'Php '.number_format($totalfees, 2) }}</h6> 
                        </div>
                    </div>
                     <div class="col100">
                        <h6 class="bold text-black" style="margin: 0px 3px; padding: 0px; line-height:10px;">Payment Schedules</h6> 
                    </div>
                    @php
                        $paymentsched = [];
                        $paypassed = 0;
                        if($payment_schedules->toArray())
                        {
                            foreach ($payment_schedules->toArray() as $key => $payment_schedule) 
                            {
                                if($payment_schedule['educational_level_id'] == NULL && $payment_schedule['year_level'] == NULL){
                                    $paypassed = 1;
                                }else{
                                    if($payment_schedule['educational_level_id'] != NULL){
                                        $paypassed = ($educational_level_id == $payment_schedule['educational_level_id']) ? 1 : 0;
                                    }
                                    if($payment_schedule['year_level'] != 0){
                                        $paypassed = ($year_level == $payment_schedule['year_level']) ? 1 : 0;
                                    }
                                }
                                if($paypassed == 1)
                                {
                                    $paymentsched[] = $payment_schedule;
                                }
                            }
                        }

                        @endphp
                            <table class="tablefees" style="width: 100%;" cellpadding="0" cellspacing="0">
                        @php
                        //FINAL COMPUTATION (AMOUNT DUE)
                        $amountdue = 0;
                        $fixedtuition = 0;
                        $fixedmisc = 0;
                        $fixedother = 0;
                        foreach ($paymentsched as $key => $ps) 
                        {
                            $topay = 0;

                            $fixedtuition = ($ps['payment_type'] == 2) ? $ps['tuition'] : 0;
                            $fixedmisc    = ($ps['payment_type'] == 2) ? $ps['miscellaneous'] : 0;
                            $fixedother   = ($ps['payment_type'] == 2) ? $ps['others'] : 0;

                            $totaltuition  = $totaltuition-$fixedtuition;
                            $miscfeetotal  = $miscfeetotal-$fixedmisc;
                            $otherfeetotal = $otherfeetotal-$fixedother;

                            $tuitioncomp = ($ps['payment_type'] == 1) ? ($ps['tuition']/100) * $totaltuition : $ps['tuition'];
                            $misccomp    = ($ps['payment_type'] == 1) ? ($ps['miscellaneous']/100) * $miscfeetotal : $ps['miscellaneous'];
                            $otherscomp  = ($ps['payment_type'] == 1) ? ($ps['others']/100) * $otherfeetotal : $ps['others'];

                            if(strcasecmp($ps['description'], 'downpayment') == 0){
                                $topay = $tuitioncomp+$misccomp+$otherscomp+$labfeetotal+$additionalfeetotal;
                                $amountdue = $topay;
                            }else{
                                $topay = $tuitioncomp+$misccomp+$otherscomp;
                                $exams[] = array('exams' => $topay);
                            }
                            @endphp
                                <tr>
                                    <td>{{ $ps['description'] }}</td>
                                    <td class="right w100">{{ number_format($topay,2) }}</td>
                                </tr>
                            @php
                        }
                    @endphp
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr><td><div class="bold">AMOUNT DUE</div></td><td class="w100 right"><div class="bold">{{ 'Php '.number_format($amountdue,2) }}</div></td></tr>
                        <tr><td colspan="2">&nbsp;</td></tr> 
                    </table>
                    <div class="col100">
                        <div class="col70">
                            <h6 class="bold text-black trimmargin">GRAND TOTAL</h6> 
                        </div>
                        <div class="col30 right">
                            <h6 class="bold text-black trimmargin">{{ 'Php '.number_format($totalfees, 2) }}</h6> 
                        </div>
                    </div>
                    <div class="col100">
                        <div class="mid" style="font-size: 12px;">Note: SCHOOL FEES ARE SUBJECT FOR ADJUSTMENT</div>
                    </div>
                    <p></p>
                    <table class="" style="width: 100%;" cellpadding="0" cellspacing="0">
                        <tr><td class="w200"><label>Approved/Verified by:</label></td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr><td class="mid" style="border-top:1px solid black;"><div class="bold">Adviser</div></td><td></td></tr>
                        <tr><td colspan="2" style="font-size: 12px !important"><span class="bold" style="font-size: 12px;">Date Printed : </span>{{ date("F d, Y") }}</td></tr>
                        <tr><td colspan="2" style="font-size: 12px !important"><span class="bold" style="font-size: 12px;">Printed By : </span>{{ Auth::user()->{ $info['info'] }->name }}</td></tr>
                    </table>
                    @if ($assessment->enrollment->validated == 0)
                        <h6 style="font-size: 12px; margin:5px auto; text-align: center;">****** Enrolment is not yet validated! ******</h6>
                    @endif
                    <div id="due" class="m-3">
                        <div style="font-size: 12px;">
                        @php
                            if($configuration)
                            {
                                $noofdays = $configuration->due;
                                $note = $configuration->note; 
                                $due = date('F d, Y', strtotime($enrollment_date . ' +'.$noofdays.' days'));
        
                         @endphp
                                {{ str_replace('DUE',$due,$note) }}
                        @php
                            }
                        @endphp
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>    
</body>
</html>
    
    
    