<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionGroup;
use App\Models\QuestionSubcategory;

class QuestionService
{
    //

    public function surveyQuestions($request)
    {
        $level = $request->filled('educational_level_id') ? $request->educational_level_id : 1;

        $results = Question::select([
            'questions.id',
            'questions.question',
            'questions.educational_level_id',
            'question_categories.name AS category',
            'question_subcategories.name AS subcategory',
            'question_groups.name AS group',
        ])
        ->leftJoin('question_categories', 'questions.question_category_id', '=', 'question_categories.id')
        ->leftJoin('question_subcategories', 'questions.question_subcategory_id', '=', 'question_subcategories.id')
        ->leftJoin('question_groups', 'questions.question_group_id', '=', 'question_groups.id')
        ->where('questions.educational_level_id', '=', $level)
        ->get();

        $groupedQuestions = $this->groupSurveyQuestions($results->toArray());

        return $groupedQuestions;
    }

    public function groupSurveyQuestions($questions)
    {
        $grouped = [];

        foreach ($questions as $question) {
            $category     = $question['category'];
            $subcategory  = $question['subcategory'];
            $group        = $question['group'];
            $questionText = $question['question'];

            if (!isset($grouped[$category])) {
                $grouped[$category] = [
                    'category' => $category,
                    'subcategory' => [],
                ];
            }

            if (!isset($grouped[$category]['subcategory'][$subcategory])) {
                $grouped[$category]['subcategory'][$subcategory] = [
                    'subcategory' => $subcategory,
                    'group' => [],
                ];
            }

            if (!isset($grouped[$category]['subcategory'][$subcategory]['group'][$group])) {
                $grouped[$category]['subcategory'][$subcategory]['group'][$group] = [
                    'group' => $group,
                    'questions' => [],
                ];
            }

            $grouped[$category]['subcategory'][$subcategory]['group'][$group]['questions'][] = [
                'id' => $question['id'],
                'question' => $questionText,
            ];
        }

        return $grouped;

    }

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

    public function saveQuestion($request)
    {
        $insert = Question::firstOrCreate($request->validated(), $request->validated());
           
        if ($insert->wasRecentlyCreated) {
            return [
                'success' => true,
                'message' => 'Question successfully added!',
                'alert'   => 'alert-success',
            ];
        }

        return ['success' => false, 'alert' => 'alert-danger', 'message' => 'Duplicate entry, question already exists!'];
    }
}
