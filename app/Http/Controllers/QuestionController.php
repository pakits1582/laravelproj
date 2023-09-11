<?php

namespace App\Http\Controllers;

use App\Libs\Helpers;
use Illuminate\Http\Request;
use App\Models\QuestionGroup;
use App\Models\QuestionCategory;
use App\Services\QuestionService;
use App\Models\QuestionSubcategory;
use App\Http\Requests\StoreCategoryRequest;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
        Helpers::setLoad(['jquery_question.js', 'select2.full.min.js']);
    }

    public function index()
    {

        return view('facultyevaluation.question.index');
    }

    public function create()
    {
        $categories    = QuestionCategory::all();
        $subcategories = QuestionSubcategory::all();
        $groups        = QuestionGroup::all();
        
        return view('facultyevaluation.question.create', compact('categories', 'subcategories', 'groups'));
    }

    public function store()
    {

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
