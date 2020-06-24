<?php

namespace App\Http\Controllers;

use App\TheoryTest;
use App\TheoryQuestion;
use Illuminate\Http\Request;

class TheoryQuestionController extends Controller
{

    public function store(Request $request, TheoryTest $test)
    {
        $data = $request->validate(['question' => 'required']);
        $question = $test->theoryquestions()->create($data);
        return response()->json($question);
    }

    public function submit(Request $request, TheoryQuestion $question)
    {
        $data = $request->validate(
            [
                'answer' => 'required'
            ]
        );
        $data['user_id'] = auth()->user()->id;
        $question->theoryanswers()->create($data);
        return response()->json($question);
    }
}
