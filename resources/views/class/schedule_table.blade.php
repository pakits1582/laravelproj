@inject('carbon', 'Carbon\Carbon')

@if(count($class_schedules) > 0)
    @php
        $week_days = array('M','T','W','TH','F','S','SU');
		$firstTime = strtotime(env('SCHEDULE_TIME_FROM'));
		$lastTime  = strtotime(env('SCHEDULE_TIME_TO'));
		$interval  = env('SCHEDULE_TIME_INTERVAL');
		$diff = ($lastTime - $firstTime) / (60 * $interval);

        $range = range($firstTime,$lastTime,$interval*60);

		$rowspan = 1;
	    $rangetime = [];

	    foreach($range as $time)
        {
	        $rangetime[] = Carbon\Carbon::createFromTimestamp($time)->format('H:i');
	    }

        function processSchedule($class_schedules, $raw_time, $day)
        {
            $schedules_for_the_day = array_filter($class_schedules, function ($item) use ($day) {
	            if ($item['day'] == $day) {
	                return true;
	            }
	            return false;
	        });

            $return_schedules = [];
            foreach ($schedules_for_the_day as $key => $schedule) 
            {
                $starttime = 0;
	            $between   = 0;
	            $endtime   = 0;
	            $faculty = '';

                $time = Carbon\Carbon::parse($raw_time);
                $fromTime = Carbon\Carbon::parse($schedule['from_time']);
                $toTime = Carbon\Carbon::parse($schedule['to_time']);

                if ($time->between($fromTime, $toTime, true)) {
                    if ($time->equalTo($fromTime)) {
                        $starttime = 1;
                    }
                    if ($time->equalTo($toTime)) {
                        $endtime = 1;
                    }
                    if ($time->greaterThan($fromTime) && $time->lessThan($toTime)) {
                        $between = 1;
                    }

                    if ($schedule['instructor_id'])
                    {
                        $fname = explode(" ", $schedule['instructor_first_name']);
                        $acronym = "";
                        foreach ($fname as $w) {
                            $acronym .= $w[0];
                        }
                        $faculty = ($schedule['instructor_first_name'] == '(TBA)') ? 'TBA' : $acronym.'. '.$schedule['instructor_last_name'];
                    }

                    $return_schedules[] = [
                        'time'         => $raw_time,
                        'class_id'     => $schedule['class_id'],
                        'class_code'   => $schedule['class_code'],
                        'room'         => $schedule['room'],
                        'from_time'    => $schedule['from_time'],
                        'to_time'      => $schedule['to_time'],
                        'section_code' => $schedule['section_code'],
                        'subject_code' => $schedule['subject_code'],
                        'starttime'    => $starttime,
                        'between'      => $between,
                        'endtime'      => $endtime,
                        'faculty'      => $faculty
                    ];
                }
            }

            return $return_schedules;
        }

        function checkconflict($class_schedules, $day, $class, $starttime, $endtime)
        {
	    	$schedules_for_the_day = array_filter($class_schedules, function ($item) use ($day) {
	            if ($item['day'] == $day) {
	                return true;
	            }
	            return false;
	        });
	        
	    	$conflicts = [];
	    	foreach ($schedules_for_the_day as $key => $schedule) 
            {
	    		if ($schedule['instructor_id'])
                {
                    $fname = explode(" ", $schedule['instructor_first_name']);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $faculty = ($schedule['instructor_first_name'] == '(TBA)') ? 'TBA' : $acronym.'. '.$schedule['instructor_last_name'];
                }

	    		if($class != $schedule['class_id'])
                {
                    if (!Helpers::rangesNotOverlapOpen(
                            Carbon\Carbon::parse($starttime),
                            Carbon\Carbon::parse($endtime),
                            Carbon\Carbon::parse($schedule['from_time']),
                            Carbon\Carbon::parse($schedule['to_time'])
                        )) 
                        {
                            $conflicts[] = [
                                'class_code'   => $schedule['class_code'],
                                'subject_code' => $schedule['subject_code'],
                                'room'         => $schedule['room'], 
                                'from_time'    => $schedule['from_time'], 
                                'to_time'      => $schedule['to_time'], 
                                'section_code' => $schedule['section_code'], 
                                'faculty'      => $faculty ?? ''
                            ];
                        }
	    		}	
	    	}
            
	    	return $conflicts;
	    }

        $processedSchedules = [];
	    foreach ($rangetime as $key => $time) 
        {
	    	$processedSchedules[$key]['time'] = $time;
	    	foreach ($week_days as $k => $day) 
            {
	    		$processedSchedules[$key]['days'][] = [
                    'day' => $day, 
                    'schedules' => processSchedule($class_schedules, $time, $day)
                ];
	    	}
	    }

        $data_table = '';
		$has_conflict = 0;
        $i = 1;
        
        if($processedSchedules)
        {
            foreach ($processedSchedules as $k => $sched) 
            {
                ++$i;
                $data_table .= '<tr>';
                if($i==2)
                {
                    $data_table .= '<td class="w100 mid fix_height" rowspan="2"><div class="time_container">';
                    $data_table .= '<div class="smalltime">'.date('h:i A', strtotime($sched['time'])).'</div>';
                    $data_table .= '<div class="bigtime">'.date('h:i A', strtotime('+30 minutes', strtotime($sched['time']))).'</div>';
                    $data_table .= '<div class="smalltime">'.date('h:i A', strtotime('+59 minutes', strtotime($sched['time']))).'</div>';
                    $data_table .='</div></td>';
                    $i=0;
                }
                foreach ($sched['days'] as $key => $sched_days) 
                {
                    if(count($sched_days['schedules']) > 0)
                    {
                        $rowspan = 0;
                        $subs = '';
                        $withconflict = 0;
                        $conflicttimes = [];
                        $betweentimes = [];
                        $endtimes = [];

                        foreach ($sched_days['schedules'] as $ks => $day_sched) 
                        {
                            if($day_sched['starttime'] == 1)
                            {
                                $hasconflicts = checkconflict(
                                    $class_schedules, 
                                    $sched_days['day'],
                                    $day_sched['class_id'],
                                    $day_sched['from_time'],
                                    $day_sched['to_time']
                                );

                                if(empty($hasconflicts))
                                {
                                    $ts1 = strtotime($day_sched['from_time']);
                                    $ts2 = strtotime($day_sched['to_time']);
                                    $seconds_diff = $ts2 - $ts1;                            
                                    $time = ($seconds_diff/60);
                                    $rowspan = $time/30;

                                    $subs .= '<div class="tabschedsub">'.$day_sched['subject_code'].' ('.$day_sched['room'].')</div>';
                                    $subs .= '<div class="tabschedfac">'.$day_sched['section_code'].'</div>';
                                    if($with_faculty == true)
                                    {
                                        $subs .= '<div class="tabschedfac">'.$day_sched['faculty'].'</div>';
                                    }
                                }else{
                                    $conflicttimes[] = [
                                        'class_code'   => $day_sched['class_code'], 
                                        'subject_code' => $day_sched['subject_code'], 
                                        'room'         => $day_sched['room'], 
                                        'starttime'    => $day_sched['from_time'], 
                                        'endtime'      => $day_sched['to_time'], 
                                        'faculty'      => $day_sched['faculty'], 
                                        'section_code' => $day_sched['section_code']
                                    ];

                                    foreach ($hasconflicts as $k => $conflict) 
                                    {
                                        $conflicttimes[] = [
                                            'class_code'   => $conflict['class_code'], 
                                            'subject_code' => $conflict['subject_code'], 
                                            'room'         => $conflict['room'], 
                                            'starttime'    => $conflict['from_time'], 
                                            'endtime'      => $conflict['to_time'], 
                                            'faculty'      => $conflict['faculty'], 
                                            'section_code' => $conflict['section_code']
                                        ];
                                    }
                                    
                                    $has_conflict = 1;
	                        	    $withconflict = 1;

                                    $minconflictstarttime = (!empty($conflicttimes)) ? min(array_column($conflicttimes, 'starttime')) : '';
                                    $maxconflictendtime = (!empty($conflicttimes)) ? max(array_column($conflicttimes, 'endtime')) : '';
                                    $ts1 = strtotime($minconflictstarttime);
                                    $ts2 = strtotime($maxconflictendtime);
                                    $seconds_diff = $ts2 - $ts1;                            
                                    $time = ($seconds_diff/60);
                                    $rowspan = $time/30;
                                }
                            }

                            if($day_sched['between'] == 1)
                            {
                                $betweentimes[] = ['starttime' => $day_sched['from_time'], 'endtime' => $day_sched['to_time']];
                            }

                            if($day_sched['endtime'] == 1)
                            {
                                $endtimes[] = ['starttime' => $day_sched['from_time'], 'endtime' => $day_sched['to_time']];
                            }
                        }

                        $tempArr = array_unique(array_column($conflicttimes, 'class_code'));
					    $confli  = array_intersect_key($conflicttimes, $tempArr);

                        $minconflictstarttime = (!empty($conflicttimes)) ? min(array_column($conflicttimes, 'starttime')) : '';
                        $maxconflictendtime = (!empty($conflicttimes)) ? max(array_column($conflicttimes, 'endtime')) : '';
                        
                        $minbetweentimes = (!empty($betweentimes)) ? min(array_column($betweentimes, 'starttime')) : '';
                        $maxbetweentimes = (!empty($betweentimes)) ? max(array_column($betweentimes, 'endtime')) : '';

                        $minendtimes = (!empty($endtimes)) ? min(array_column($endtimes, 'starttime')) : '';
                        $maxendtimes = (!empty($endtimes)) ? max(array_column($endtimes, 'endtime')) : '';

                        if(strtotime($day_sched['time']) > strtotime($minconflictstarttime) && strtotime($day_sched['time']) < strtotime($maxconflictendtime)){
	            		//$data_table .= '<td>empty</td>';
                        }else{
                            if(strtotime($day_sched['time']) > strtotime($minbetweentimes) && strtotime($day_sched['time']) < strtotime($maxbetweentimes)){
                                //$data_table .= '<td>empty</td>';
                            }else{
                                if(strtotime($day_sched['time']) > strtotime($minendtimes) && strtotime($day_sched['time']) < strtotime($maxendtimes)){
                                    //$data_table .= '<td>empty</td>';
                                }else{
                                    if($rowspan != 0){
                                        $class = ($withconflict == 1) ? 'dissolved' : 'tutorial'; 
                                        if($withconflict == 1){
                                            foreach ($confli as $key => $c) {
                                                $subs .= '<div class="tabschedsub">'.$c['subject_code'].' ('.$c['room'].')</div>';
                                                $subs .= '<div class="tabschedfac">'.$c['section_code'].'</div>';
                                                if($with_faculty == true)
                                                {
                                                    $subs .= '<div class="tabschedfac">'.$c['faculty'].'</div>';
                                                }
                                            }
                                            $subs .= '<span class="tabschedconf">ARE CONFLICT</span>';
                                            $data_table .= '<td rowspan="'.$rowspan.'" class="'.$class.' mid">'.$subs.'</td>';
                                        }else{
                                            $data_table .= '<td rowspan="'.$rowspan.'" class="'.$class.' mid">'.$subs.'</td>';
                                        }    	
                                    }else{
                                        $data_table .= '<td>&nbsp;</td>';
                                    }
                                }
                            }
                        }
                    }else{
                        $data_table .= '<td>&nbsp;</td>';
                    }
                }
                $data_table .= '</tr>';
            }
        }
    @endphp
    <table id="scheduletable" class="" style="font-size: 14px;">
        <thead>
            <tr>
                <th></th>
                <th scope="col">Monday</th>
                <th scope="col">Tuesday</th>
                <th scope="col">Wednesday</th>
                <th scope="col">Thursday</th>
                <th scope="col">Friday</th>
                <th scope="col">Saturday</th>
                <th scope="col">Sunday</th>
            </tr>
        </thead>
        @php
            echo $data_table
        @endphp
    </table>
    <input type="hidden" name="has_conflict" id="has_conflict" value="{{ $has_conflict}}" />
@endif

