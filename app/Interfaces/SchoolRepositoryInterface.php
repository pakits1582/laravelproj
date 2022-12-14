<?php

namespace App\Interfaces;

interface SchoolRepositoryInterface
{
    public function getAllSchools();

    public function getSchoolById($schoolId);

    public function createSchool(array $arr, array $schoolDetails);

    public function checkDuplicateOnUpdate(array $array);

    public function updateSchool($schoolId, array $newDetails);

    public function deleteSchool($schoolId);
}
