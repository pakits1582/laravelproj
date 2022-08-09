<?php

namespace App\Libs;

class Helpers
{
    public static $load;
    
    public static function setLoad($loadfile)
    {
		if(isset($loadfile)){
			self::$load = $loadfile;
		}
	}
	
	public static function getLoad()
    {
		return self::$load;
	}

    public static function userAccessArray()
    {
        return array(
            array(
                'title' => 'General',
                'access' => array(
                    array('header' => 'Configuration' ,'link' => 'configuration', 'id' => 'configuration'),
                    array('header' => 'Colleges'      ,'link' => 'college', 'id' => 'college'),
                    array('header' => 'Periods'       ,'link' => 'period', 'id' => 'period'),
                    array('header' => 'School'        ,'link' => 'school', 'id' => 'school'),
                    array('header' => 'User Accounts' ,'link' => 'user', 'id' => 'user'),
                    array('header' => 'Audit Log'     ,'link' => 'configuration/auditlog', 'id' => 'auditlog')
                    )
            ),

            array(
                'title' => 'Offerings',
                'access' => array(
                    array('header' => 'Programs'    ,'link' => 'program', 'id' => 'program'),
                    array('header' => 'Departments' ,'link' => 'department', 'id' => 'department'),
                    array('header' => 'Curriculum'  ,'link' => 'curriculum', 'id' => 'curriculum'),
                    array('header' => 'Instructors' ,'link' => 'instructor', 'id' => 'instructor'),
                    array('header' => 'Rooms'       ,'link' => 'room', 'id' => 'room'),
                    array('header' => 'Sections'    ,'link' => 'section', 'id' => 'section'),
                    array('header' => 'Subjects'    ,'link' => 'subject', 'id' => 'subject')
                )
            ),
            array(
                'title' => 'Scheduling',
                'access' => array(
                    array('header' => 'Classes'             ,'link' => 'classes', 'id' => 'classes'),
                    array('header' => 'Slot Monitoring'     ,'link' => 'classes/monitoring', 'id' => 'classes_monitoring'),
                    array('header' => 'Section Monitoring'  ,'link' => 'section/monitoring', 'id' => 'section_monitoring'),
                    array('header' => 'General Schedule'    ,'link' => 'generalschedule', 'id' => 'generalschedule'),
                    array('header' => 'Room Assignment'     ,'link' => 'room/assignment', 'id' => 'roomassignment'),
                    array('header' => 'Faculty Evaluation'  ,'link' => 'facultyevaluation', 'id' => 'facultyevaluation')
                )
            ),   

            array(
                'title' => 'Enrolment',
                'access' => array(
                    array('header' => 'Adding/Dropping'   ,'link' => 'adddrop', 'id' => 'adddrop'),
                    array('header' => 'Assessment'        ,'link' => 'assessment', 'id' => 'assessment'),
                    array('header' => 'Enrolment'         ,'link' => 'enrolment', 'id' => 'enrolment'),
                    array('header' => 'Validation'        ,'link' => 'validation', 'id' => 'validation'),
                    array('header' => 'Re-assessment'     ,'link' => 'reassessment', 'id' => 'reassessment'),
                    array('header' => 'Unpaid Assessment' ,'link' => 'assessment/unpaid', 'id' => 'unpaidassessment'),
                    array('header' => 'Unsaved Enrolment' ,'link' => 'enrolment/unsaved', 'id' => 'unsavedenrolment')
                )
            ),   
            
            array(
                'title' => 'Services',
                'access' => array(
                    array('header' => 'Students'          ,'link' => 'student', 'id' => 'student'),
                    array('header' => 'Class List'        ,'link' => 'classlist', 'id' => 'classlist'),
                    array('header' => 'Grading System'    ,'link' => 'gradingsystem', 'id' => 'gradingsystem'),
                    array('header' => 'Master List'       ,'link' => 'masterlist', 'id' => 'masterlist'),
                    array('header' => 'Faculty Load'      ,'link' => 'facultyload', 'id' => 'facultyload'),
                    array('header' => 'Enrolment Summary' ,'link' => 'enrolmentsummary', 'id' => 'enrolmentsummary'),
                    array('header' => 'Attendace'         ,'link' => 'attendance', 'id' => 'attendance'),
                    array('header' => 'Student Schedule'  ,'link' => 'studentschedule', 'id' => 'studentschedule')
                )
            ),
            
            array(
                'title' => 'Process',
                'access' => array(
                    array('header' => 'Application'         ,'link' => 'application', 'id' => 'application'),
                    array('header' => 'Admission'           ,'link' => 'admission', 'id' => 'admission'),
                    array('header' => 'Admission Documents' ,'link' => 'admission/documents', 'id' => 'admissiondocuments'),
                    array('header' => 'Registrar Reports'   ,'link' => 'registrarreports', 'id' => 'registrarreports')
                )
            ),   
           
            array(
                'title' => 'Accounting',
                'access'  => array(
                    array('header' => 'Fees Library'       ,'link' => 'fees', 'id' => 'fees'),
                    array('header' => 'Payment Option'     ,'link' => 'paymentoption', 'id' => 'paymentoption'),
                    array('header' => 'Setup Fees'         ,'link' => 'fees/setup', 'id' => 'setupfees'),
                    array('header' => 'Receipt Entry',      'link' => 'receipt', 'id' => 'receipt'),
                    array('header' => 'Daily Collection'   ,'link' => 'dailycollection', 'id' => 'dailycollection'),
                    array('header' => 'Accounting Reports' ,'link' => 'accountingreports', 'id' => 'accountingreports')
                )
            ),

            array(
                'title' => 'Grades',
                'access' => array(
                    array('header' => 'Evaluation'       ,'link' => 'evaluation', 'id' => 'evaluation'),
                    array('header' => 'Grade File'       ,'link' => 'grade', 'id' => 'grade'),
                    array('header' => 'External Grades'  ,'link' => 'gradeexternal', 'id' => 'gradeexternal'),
                    array('header' => 'Internal Grades'  ,'link' => 'gradeinternal', 'id' => 'gradeinternal'),
                    array('header' => 'Grading Sheet'    ,'link' => 'gradingsheet', 'id' => 'gradingsheet')
                )
            ),
            
            array(
                'title' => 'Student Ledger',
                'access' => array(
                    array('header' => 'Post Charge'                ,'link' => 'postcharge', 'id' => 'postcharge'),
                    array('header' => 'Scholarship/Discount'       ,'link' => 'scholarshipdiscount', 'id' => 'scholarshipdiscount'),
                    array('header' => 'Statement of Accounts'      ,'link' => 'studentledger', 'id' => 'studentledger'),
                    array('header' => 'Scholarship/Discount Grant' ,'link' => 'scholarshipdiscount/grant', 'id' => 'scholarshipdiscountgrant'),
                    array('header' => 'Student Adjustment'         ,'link' => 'studentadjustment', 'id' => 'studentadjustment')
                )
            )
        );
      
    }

    public static function searchUSerAccess($array, $val, $column)
    {
        foreach ($array as $key => $value) {
            foreach ($value['access'] as $k => $v) {
                if($v[$column] == $val){
                    return array_merge(['title' => $value['title']], $v);
                }
            }
        }
    }
}