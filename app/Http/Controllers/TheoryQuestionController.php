<?php

namespace App\Http\Controllers;

use App\TheoryAnswer;
use App\TheoryTest;
use App\TheoryQuestion;
use App\TheoryResult;
use Illuminate\Http\Request;

class TheoryQuestionController extends Controller
{

    public function store(Request $request, TheoryTest $test)
    {
        $data = $request->validate(['question' => 'required']);
        $question = $test->theoryquestions()->create($data);
        event(new \App\Events\UpdateTheoryTests());
        return response()->json($question);
    }
    public function edit(Request $request, TheoryQuestion $question)
    {
        $data = $request->validate(['question' => 'required']);
        $question->update($data);
        event(new \App\Events\UpdateTheoryTests());
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
        event(new \App\Events\UpdateSubmissions());
        return response()->json($question);
    }
    public function resubmit(Request $request, TheoryAnswer $answer){
        $data = $request->validate([
            'answer'=>'required'
        ]);
        $answer->update($data);
        event(new \App\Events\UpdateSubmissions());
        return response()->json($answer);

    }
    public function finishmark(Request $request, TheoryTest $test){
        $answer = TheoryAnswer::where('id',$request->answer_id)->first();
        //*Apply corrections to answer
        $new_answer = $request->validate([
            'answer'=>'required'
        ]);
        $answer->update($new_answer);


        //*Store Score
        $result= $request->validate([
            'score'=>'required'
        ]);
        $result['user_id'] = $answer->user_id;
        $result['total'] = $test->total;

        //*Check if it has been marked before
        $marked = $test->theoryresults()->where('user_id',$answer->user_id)->get();
        if(count($marked)>0){
            //*then update it
            $marked->first()->update($result);
        }else{
            //*else create it
            $result = $test->theoryresults()->create($result);
        }
        event(new \App\Events\UpdateSubmissions());
        return response()->json($result);
    }
}
