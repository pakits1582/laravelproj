<?php

namespace App\Repositories;

use App\Interfaces\SchoolRepositoryInterface;
use App\Models\School;

class SchoolRepository implements SchoolRepositoryInterface
{
    protected $model;

    public function __construct(School $model)
    {
        $this->model = $model;
    }

    public function getAllSchools()
    {
        return $this->model->allschools();
    }

    public function createSchool(array $arr, array $SchoolDetails)
    {
        return School::firstOrCreate($arr, $SchoolDetails);
    }

    public function getSchoolById($SchoolId)
    {
        return School::findOrFail($SchoolId);
    }

    public function checkDuplicateOnUpdate($array)
    {
        return School::where($array)->first();
    }

    public function updateSchool($SchoolId, array $newDetails)
    {
        return School::whereId($SchoolId)->update($newDetails);
    }

    public function deleteSchool($SchoolId)
    {
        School::destroy($SchoolId);
    }
}
