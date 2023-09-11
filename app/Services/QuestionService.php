<?php

namespace App\Services;

use App\Models\QuestionCategory;
use App\Models\QuestionGroup;
use App\Models\QuestionSubcategory;

class QuestionService
{
    //

    public function addQuestionCategory($request)
    {
        switch ($request->field) {
            case 'question_category_id':
                $categories = QuestionCategory::all();
                break;
            case 'question_subcategory_id':
                $categories = QuestionSubcategory::all();
                break;
            case 'question_group_id':
                $categories = QuestionGroup::all();
                break;
            default:
                $categories = [];
                break;
        }

        return $categories;
    }

    public function saveCategory($request)
    {
        switch ($request->field) {
            case 'question_category_id':
                $insert = QuestionCategory::firstOrCreate(['name' => $request->name], $request->validated());
                break;
            case 'question_subcategory_id':
                $insert = QuestionSubcategory::firstOrCreate(['name' => $request->name], $request->validated());
                break;
            case 'question_group_id':
                $insert = QuestionGroup::firstOrCreate(['name' => $request->name], $request->validated());
                break;
            default:
                $insert = [];
                break;
        }

        if ($insert->wasRecentlyCreated) {
            return [
                'success' => true,
                'message' => 'Category successfully added!',
                'alert'   => 'alert-success',
                'data'    => $insert,
                'field'   => $request->field,
            ];
        }

        return ['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, category already exists!'];
    }
}
