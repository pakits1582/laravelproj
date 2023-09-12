<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\QuestionGroup;
use App\Models\QuestionCategory;
use App\Services\QuestionService;
use App\Models\QuestionSubcategory;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
        Helpers::setLoad(['jquery_question.js', 'select2.full.min.js']);
    }

    public function index(Request $request)
    {
        $questions = $this->questionService->surveyQuestions($request);

        if($request->ajax())
        {
            return view('facultyevaluation.question.return_surveyquestions', compact('questions'));
        }

        return view('facultyevaluation.question.index', compact('questions'));
    }

    public function create()
    {
        $categories    = QuestionCategory::all();
        $subcategories = QuestionSubcategory::all();
        $groups        = QuestionGroup::all();
        
        return view('facultyevaluation.question.create', compact('categories', 'subcategories', 'groups'));
    }

    public function store(StoreQuestionRequest $request)
    {
        $savequestion = $this->questionService->saveQuestion($request);

        return response()->json($savequestion);
    }

    public function edit(Question $question)
    {   
        $categories    = QuestionCategory::all();
        $subcategories = QuestionSubcategory::all();
        $groups        = QuestionGroup::all();
        
        return view('facultyevaluation.question.edit', compact('question', 'categories', 'subcategories', 'groups'));
    }

    public function update(UpdateQuestionRequest $request, Question $questioninfo)
    {   
        $questioninfo->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Question successfully updated!',
            'alert'   => 'alert-success',
        ]);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question successfully deleted!',
            'alert'   => 'alert-success',
        ]);
    }
    
    public function addquestioncategory(Request $request)
    {
        $categories = $this->questionService->addQuestionCategory($request);
        $field = $request->field;

        return view('facultyevaluation.question.create_category', compact('categories', 'field'));

    }

    public function savecategory(StoreCategoryRequest $request)
    {
        $savecategory = $this->questionService->saveCategory($request);

        return response()->json($savecategory);
    }
}
