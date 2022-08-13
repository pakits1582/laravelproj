<?php

namespace App\Libs;

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
                    ['header' => 'General Schedule', 'link' => 'generalschedules', 'id' => 'generalschedule'],
                    ['header' => 'Room Assignment', 'link' => 'rooms/assignment', 'id' => 'roomassignment'],
                    ['header' => 'Faculty Evaluation', 'link' => 'facultyevaluations', 'id' => 'facultyevaluation'],
                ],
            ],

            [
                'title' => 'Enrolment',
                'access' => [
                    ['header' => 'Adding/Dropping', 'link' => 'adddrop', 'id' => 'adddrop'],
                    ['header' => 'Assessment', 'link' => 'assessments', 'id' => 'assessment'],
                    ['header' => 'Enrolment', 'link' => 'enrolments', 'id' => 'enrolment'],
                    ['header' => 'Validation', 'link' => 'validations', 'id' => 'validation'],
                    ['header' => 'Re-assessment', 'link' => 'reassessments', 'id' => 'reassessment'],
                    ['header' => 'Unpaid Assessment', 'link' => 'assessments/unpaid', 'id' => 'unpaidassessment'],
                    ['header' => 'Unsaved Enrolment', 'link' => 'enrolments/unsaved', 'id' => 'unsavedenrolment'],
                ],
            ],

            [
                'title' => 'Services',
                'access' => [
                    ['header' => 'Students', 'link' => 'students', 'id' => 'student'],
                    ['header' => 'Class List', 'link' => 'classlists', 'id' => 'classlist'],
                    ['header' => 'Grading System', 'link' => 'gradingsystems', 'id' => 'gradingsystem'],
                    ['header' => 'Master List', 'link' => 'masterlists', 'id' => 'masterlist'],
                    ['header' => 'Faculty Load', 'link' => 'facultyloads', 'id' => 'facultyload'],
                    ['header' => 'Enrolment Summary', 'link' => 'enrolmentsummary', 'id' => 'enrolmentsummary'],
                    ['header' => 'Attendace', 'link' => 'attendances', 'id' => 'attendance'],
                    ['header' => 'Student Schedule', 'link' => 'studentschedules', 'id' => 'studentschedule'],
                ],
            ],

            [
                'title' => 'Process',
                'access' => [
                    ['header' => 'Application', 'link' => 'applications', 'id' => 'application'],
                    ['header' => 'Admission', 'link' => 'admissions', 'id' => 'admission'],
                    ['header' => 'Admission Documents', 'link' => 'admissions/documents', 'id' => 'admissiondocuments'],
                    ['header' => 'Registrar Reports', 'link' => 'registrarreports', 'id' => 'registrarreports'],
                ],
            ],

            [
                'title' => 'Accounting',
                'access' => [
                    ['header' => 'Fees Library', 'link' => 'fees', 'id' => 'fees'],
                    ['header' => 'Payment Option', 'link' => 'paymentoptions', 'id' => 'paymentoption'],
                    ['header' => 'Setup Fees', 'link' => 'fees/setup', 'id' => 'setupfees'],
                    ['header' => 'Receipt Entry',      'link' => 'receipts', 'id' => 'receipt'],
                    ['header' => 'Daily Collection', 'link' => 'dailycollections', 'id' => 'dailycollection'],
                    ['header' => 'Accounting Reports', 'link' => 'accountingreports', 'id' => 'accountingreports'],
                ],
            ],

            [
                'title' => 'Grades',
                'access' => [
                    ['header' => 'Evaluation', 'link' => 'evaluations', 'id' => 'evaluation'],
                    ['header' => 'Grade File', 'link' => 'grades', 'id' => 'grade'],
                    ['header' => 'External Grades', 'link' => 'gradeexternals', 'id' => 'gradeexternal'],
                    ['header' => 'Internal Grades', 'link' => 'gradeinternals', 'id' => 'gradeinternal'],
                    ['header' => 'Grading Sheet', 'link' => 'gradingsheets', 'id' => 'gradingsheet'],
                ],
            ],

            [
                'title' => 'Student Ledger',
                'access' => [
                    ['header' => 'Post Charge', 'link' => 'postcharges', 'id' => 'postcharge'],
                    ['header' => 'Scholarship/Discount', 'link' => 'scholarshipdiscounts', 'id' => 'scholarshipdiscount'],
                    ['header' => 'Statement of Accounts', 'link' => 'studentledgers', 'id' => 'studentledger'],
                    ['header' => 'Scholarship/Discount Grant', 'link' => 'scholarshipdiscounts/grant', 'id' => 'scholarshipdiscountgrant'],
                    ['header' => 'Student Adjustment', 'link' => 'studentadjustments', 'id' => 'studentadjustment'],
                ],
            ],
        ];
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
}
