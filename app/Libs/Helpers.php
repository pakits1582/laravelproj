<?php

namespace App\Libs;

use App\Models\Useraccess;

class Helpers
{
    public static $load;

    public static function setLoad($loadfile)
    {
        if (isset($loadfile)) {
            self::$load = $loadfile;
        }
    }

    public static function getLoad()
    {
        return self::$load;
    }

    public static function userAccessArray()
    {
        return [
            [
                'title' => 'General',
                'access' => [
                    ['header' => 'Configurations', 'link' => 'configurations', 'id' => 'configuration'],
                    ['header' => 'Colleges', 'link' => 'colleges', 'id' => 'college'],
                    ['header' => 'Periods', 'link' => 'periods', 'id' => 'period'],
                    ['header' => 'Schools', 'link' => 'schools', 'id' => 'school'],
                    ['header' => 'User Accounts', 'link' => 'users', 'id' => 'user'],
                    ['header' => 'Audit Logs', 'link' => 'configurations/auditlogs', 'id' => 'auditlog'],
                ],
            ],

            [
                'title' => 'Offerings',
                'access' => [
                    ['header' => 'Programs', 'link' => 'programs', 'id' => 'program'],
                    ['header' => 'Departments', 'link' => 'departments', 'id' => 'department'],
                    ['header' => 'Curriculum', 'link' => 'curriculum', 'id' => 'curriculum'],
                    ['header' => 'Instructors', 'link' => 'instructors', 'id' => 'instructor'],
                    ['header' => 'Rooms', 'link' => 'rooms', 'id' => 'room'],
                    ['header' => 'Sections', 'link' => 'sections', 'id' => 'section'],
                    ['header' => 'Subjects', 'link' => 'subjects', 'id' => 'subject'],
                ],
            ],
            [
                'title' => 'Scheduling',
                'access' => [
                    ['header' => 'Classes', 'link' => 'classes', 'id' => 'classes'],
                    ['header' => 'Slot Monitoring', 'link' => 'classes/monitoring', 'id' => 'classes_monitoring'],
                    ['header' => 'Section Monitoring', 'link' => 'sections/monitoring', 'id' => 'section_monitoring'],
                    ['header' => 'General Schedules', 'link' => 'generalschedules', 'id' => 'generalschedule'],
                    ['header' => 'Room Assignments', 'link' => 'rooms/assignment', 'id' => 'roomassignment'],
                    ['header' => 'Faculty Evaluations', 'link' => 'facultyevaluations', 'id' => 'facultyevaluation'],
                ],
            ],

            [
                'title' => 'Enrolment',
                'access' => [
                    ['header' => 'Adding/Dropping', 'link' => 'adddrop', 'id' => 'adddrop'],
                    ['header' => 'Assessments', 'link' => 'assessments', 'id' => 'assessment'],
                    ['header' => 'Enrolments', 'link' => 'enrolments', 'id' => 'enrolment'],
                    ['header' => 'Validations', 'link' => 'validations', 'id' => 'validation'],
                    ['header' => 'Re-assessments', 'link' => 'reassessments', 'id' => 'reassessment'],
                    ['header' => 'Unpaid Assessments', 'link' => 'assessments/unpaid', 'id' => 'unpaidassessment'],
                    ['header' => 'Unsaved Enrolments', 'link' => 'enrolments/unsaved', 'id' => 'unsavedenrolment'],
                ],
            ],

            [
                'title' => 'Services',
                'access' => [
                    ['header' => 'Students', 'link' => 'students', 'id' => 'student'],
                    ['header' => 'Class List', 'link' => 'classlists', 'id' => 'classlist'],
                    ['header' => 'Grading System', 'link' => 'gradingsystems', 'id' => 'gradingsystem'],
                    ['header' => 'Master List', 'link' => 'masterlists', 'id' => 'masterlist'],
                    ['header' => 'Faculty Loads', 'link' => 'facultyloads', 'id' => 'facultyload'],
                    ['header' => 'Enrolment Summary', 'link' => 'enrolmentsummary', 'id' => 'enrolmentsummary'],
                    ['header' => 'Attendances', 'link' => 'attendances', 'id' => 'attendance'],
                    ['header' => 'Student Schedules', 'link' => 'studentschedules', 'id' => 'studentschedule'],
                ],
            ],

            [
                'title' => 'Process',
                'access' => [
                    ['header' => 'Applications', 'link' => 'applications', 'id' => 'application'],
                    ['header' => 'Admissions', 'link' => 'admissions', 'id' => 'admission'],
                    ['header' => 'Admission Documents', 'link' => 'admissions/documents', 'id' => 'admissiondocuments'],
                    ['header' => 'Registrar Reports', 'link' => 'registrarreports', 'id' => 'registrarreports'],
                ],
            ],

            [
                'title' => 'Accounting',
                'access' => [
                    ['header' => 'Fees Library', 'link' => 'fees', 'id' => 'fees'],
                    ['header' => 'Payment Schedules', 'link' => 'paymentschedules', 'id' => 'paymentschedules'],
                    ['header' => 'Setup Fees', 'link' => 'fees/setup', 'id' => 'setupfees'],
                    ['header' => 'Receipt Entry',      'link' => 'receipts', 'id' => 'receipt'],
                    ['header' => 'Daily Collections', 'link' => 'dailycollections', 'id' => 'dailycollection'],
                    ['header' => 'Accounting Reports', 'link' => 'accountingreports', 'id' => 'accountingreports'],
                ],
            ],

            [
                'title' => 'Grades',
                'access' => [
                    ['header' => 'Evaluations', 'link' => 'evaluations', 'id' => 'evaluation'],
                    ['header' => 'Grade Files', 'link' => 'grades', 'id' => 'grade'],
                    ['header' => 'External Grades', 'link' => 'gradeexternals', 'id' => 'gradeexternal'],
                    ['header' => 'Internal Grades', 'link' => 'gradeinternals', 'id' => 'gradeinternal'],
                    ['header' => 'Grading Sheets', 'link' => 'gradingsheets/faculty', 'id' => 'gradingsheet'],
                ],
            ],

            [
                'title' => 'Student Ledger',
                'access' => [
                    ['header' => 'Post Charges', 'link' => 'postcharges', 'id' => 'postcharge'],
                    ['header' => 'Scholarship/Discounts', 'link' => 'scholarshipdiscounts', 'id' => 'scholarshipdiscount'],
                    ['header' => 'Statement of Accounts', 'link' => 'studentledgers', 'id' => 'studentledger'],
                    ['header' => 'Scholarship Grants', 'link' => 'scholarshipdiscounts/grant', 'id' => 'scholarshipdiscountgrant'],
                    ['header' => 'Student Adjustments', 'link' => 'studentadjustments', 'id' => 'studentadjustment'],
                ],
            ],

        ];
    }

    public static function studentDefaultAccesses()
    {
        $studentAccesses = [
            ['access' => 'assessments/studentassessment', 'title' => 'Assessment', 'category' => 'Student Menu'], 
            ['access' => 'evaluations/studentevaluation', 'title' => 'Evaluation', 'category' => 'Student Menu'], 
            ['access' => 'grades/studentgrade', 'title' => 'Grades', 'category' => 'Student Menu'], 
            ['access' => 'registration', 'title' => 'Registration', 'category' => 'Student Menu'],
            ['access' => 'students/profile', 'title' => 'Profile', 'category' => 'Student Menu'], 
            ['access' => 'studentledgers/studentaccountledger', 'title' => 'Account', 'category' => 'Student Menu'],
            ['access' => 'attendances/studentattendance', 'title' => 'Attendance', 'category' => 'Student Menu'],
            ['access' => 'facultyevaluations/studentfacultyevaluation', 'title' => 'Faculty Evaluation', 'category' => 'Student Menu']
        ];

        return $studentAccesses;
    }

    public static function instructorDefaultAccesses()
    {
        $instructorAccesses = [
            ['access' => 'facultyloads/facultyload', 'title' => 'Faculty Load', 'category' => 'Faculty Menu'],
            ['access' => 'classlist/facultyclasslist', 'title' => 'Faculty Class List', 'category' => 'Faculty Menu'],
            ['access' => 'instructors/profile', 'title' => 'Faculty Profile', 'category' => 'Faculty Menu'],
            ['access' => 'gradingsheets', 'title' => 'Grading Sheet', 'category' => 'Faculty Menu'],
            ['access' => 'gradegenerator', 'title' => 'Grade Generator', 'category' => 'Faculty Menu'],
        ];

        return $instructorAccesses;
    }

    public static function searchUSerAccess($array, $val, $column)
    {
        foreach ($array as $key => $value) {
            foreach ($value['access'] as $k => $v) {
                if ($v[$column] == $val) {
                    return array_merge(['title' => $value['title']], $v);
                }
            }
        }
    }

    public static function userAccessCategories($array)
    {
        if ($array) {
            $categories = array_unique(array_column($array, 'category'));

            $accesses = [];
            foreach ($categories as $key => $value) {
                $accesses[$key] = ['category' => $value];
                foreach ($array as $k => $v) {
                    if ($value == $v['category']) {
                        $accesses[$key]['access'][] = $v;
                    }
                }
            }

            return $accesses;
        }
    }

    public static function menuCategoryIcon($category)
    {
        switch ($category) {
            case 'Configuration':
                return 'fa-cog';
                break;
            case 'Offerings':
                return 'fa-folder-open';
                break;
            case 'Scheduling':
                return 'fa-book';
                break;
            case 'Enrolment':
                return 'fa-list-alt';
                break;
            case 'Services':
                return 'fa-cogs';
                break;
            case 'Process':
                return 'fa-calendar';
                break;
            case 'Accounting':
                return 'fa-credit-card';
                break;
            case 'Grades':
                return 'fa-graduation-cap';
                break;
            case 'Student Ledger':
                return 'fa-suitcase';
                break;
            default:
                return 'fa-cog';
                break;
        }
    }

    public static function getDesignation($designation)
    {
        switch ($designation) {
            case 1:
                return 'Teacher';
                break;
            case 2:
                return 'Program Head';
                break;
            case 3:
                return 'Department Head';
                break;
            case 4:
                return 'Dean';
                break;
            case 5:
                return 'Professor';
                break;
            case 6:
                return 'Others';
                break;
            default:
                return '';
                break;
        }
    }

    public static function romanic_number($integer, $upcase = true)
    {
        $table = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }

    public static function getConfigschedtype($type)
    {
        switch ($type) {
            case 'enrolment':
                return 'Student Enrolment';
                break;
            case 'addingdropping':
                return 'Adding Dropping';
                break;
            case 'student_registration':
                return 'Student Online Registration';
                break;
            case 'grade_posting':
                return 'Student Grade Viewing';
                break;
            case 'final_grade_submission':
                return 'Faculty Final Grade Submission';
                break;
            case 'facultyload_posting':
                return 'Faculty Load Posting';
                break;
            case 'class_scheduling':
                return 'Class Scheduling';
                break;
            case 'Faculty Evaluation':
                return 'Others';
                break;
            default:
                return '';
                break;
        }
    }

    public static function  is_column_in_array($value,$column,$array)
    {
        $key = array_search($value, array_column($array, $column));
        if ($key !== false) {
            return $key;
        }
        return false;
    }

    public static function filterby_multiple_values($value, $array)
    {
        foreach($array as $k => $v)
        {
            if( $value === array_intersect($v, $value))
            {
                return $k;
                break;
            }
        }
    }

    public static function filter(array $params, array $arr){
        $out = array();
        foreach($arr as $key=>$item){
           $diff = array_diff_assoc($item,$params);
      
           if (count($diff)==1) // if count diff == 1 - Ok
              $out[$key] = $item;
       }
       return $out;

    }

    public static function array_flatten($array) { 
        if (!is_array($array)) { 
          return false; 
        } 
        $result = array(); 
        foreach ($array as $key => $value) { 
            if (is_array($value)) 
            { 
                $result = array_merge($result, self::array_flatten($value)); 
            } else { 
                $result[$key] = $value; 
            } 
        } 
        return $result; 
    } 

    public static function academicStatus($status)
    {
        switch ($status) {
            case 1:
                return 'Old';
                break;
            case 2:
                return 'New';
                break;
            case 3:
                return 'Graduated';
                break;
            case 4:
                return 'Expelled';
                break;
            default:
                return '';
                break;
        }
    }

    public static function yearLevel($level)
    {
        switch ($level) {
            case 1:
                return 'First Year';
                break;
            case 2:
                return 'Second Year';
                break;
            case 3:
                return 'Third Year';
                break;
            case 4:
                return 'Fourth Year';
                break;
            case 5:
                return 'Fifth Year';
                break;
            case 6:
                return 'Sixth Year';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getAccessAbility($accesses, $access, $ability)
    {
        $key = self::is_column_in_array($access, 'access', $accesses);

        if($key !== false){
            return $accesses[$key][$ability];
        }
       
    }
}
    