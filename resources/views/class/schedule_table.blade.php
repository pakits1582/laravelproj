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
	        $rangetime[] = \Carbon\Carbon::createFromTimestamp($time)->format('H:i');
	    }

        function processSchedule($class_schedules, $time, $day)
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

                $time = Carbon::parse($time);
                $fromTime = Carbon::parse($schedule['from_time']);
                $toTime = Carbon::parse($schedule['to_time']);

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
                        $fname = explode(" ", $schedule['first_name']);
                        $acronym = "";
                        foreach ($fname as $w) {
                            $acronym .= $w[0];
                        }
                        $faculty = ($schedule['first_name'] == '(TBA)') ? 'TBA' : $acronym.'. '.$section_subject->instructor->schedule['last_name'];
                    }

                    $return_schedules[] = [
                        'time'         => $time,
                        'class_id'     => $schedule['classid'],
                        'class_code'   => $schedule['class_code'],
                        'room'         => $schedule['room'],
                        'from_time'    => $schedule['from_time'],
                        'to_time'      => $schedule['to_time'],
                        'section_code' => $schedule['section_code'],
                        'starttime'    => $starttime,
                        'between'      => $between,
                        'endtime'      => $endtime,
                        'faculty'      => $faculty
                    ];
                }
            }
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
                    $fname = explode(" ", $schedule['first_name']);
                    $acronym = "";
                    foreach ($fname as $w) {
                        $acronym .= $w[0];
                    }
                    $faculty = ($schedule['first_name'] == '(TBA)') ? 'TBA' : $acronym.'. '.$section_subject->instructor->schedule['last_name'];
                }

	    		if($class != $schedule['class_id'])
                {
                    if (!Helpers::rangesNotOverlapOpen(
                            Carbon::parse($starttime),
                            Carbon::parse($endtime),
                            Carbon::parse($schedule['from_time']),
                            Carbon::parse($schedule['to_time'])
                        )) 
                        {
                            $conflicts[] = [
                                'class_code'   => $value['class_code'],
                                'room'         => $value['room'], 
                                'from_time'    => $value['from_time'], 
                                'to_time'      => $value['to_time'], 
                                'section_code' => $value['section_code'], 
                                'faculty'      => $faculty ?? ''
                            ];
                        }
	    		}	
	    	}
            
	    	return $conflicts;
	    }

    @endphp

    
@endif