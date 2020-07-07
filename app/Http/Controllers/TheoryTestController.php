<?php

namespace App\Http\Controllers;

use App\Classroom;
use Carbon\Carbon;
use App\TheoryTest;
use App\TheoryAnswer;
use App\TheoryQuestion;
use Illuminate\Http\Request;

class TheoryTestController extends Controller
{
    public function checkclassroom($request)
    {
        $slug = $request->slug;
        $classroom = Classroom::where('slug', $slug)->first();
        return $classroom;
    }
    public function index(Request $request)
    {
        $now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addHour())->format('Y-m-d H:i:s');
        $classroom = $this->checkclassroom($request);
        $tests = $classroom->theorytests()
            ->where('deadline', ">", $now)
            ->get();
        return response()->json($tests);
    }
    public function mark(Request $request)
    {
        $classroom = $this->checkclassroom($request);

        $tests = $classroom->theorytests()->get();
        $tests = collect($tests);
        $tests = $tests->sort(function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a->created_at > $b->created_at) ? -1 : 1;
        })->values()->all();
        return response()->json($tests);
    }
    public function store(Request $request)
    {
        $getclassroom = $this->checkclassroom($request);
        $data = $request->validate([
            'title' => 'required',
            'deadline' => 'required',
            'total' => 'required'
        ]);
        $classroom = $getclassroom;
        $test = $classroom->theorytests()->create($data);
        return response()->json($test);
    }
    public function show(TheoryTest $test)
    {
            $question = $test->theoryquestions()->first();
            $test['theoryquestion']= $question;
            $test->theoryquestion['theoryanswer'] = $question->theoryanswers()->where('user_id',auth()->user()->id)->get();
            return response()->json($test);
    }
    public function showdetails(TheoryTest $test)
    {
            return response()->json($test);
    }
    public function submissions(TheoryTest $test){
        $submissions = $test->theoryquestions()->first()->theoryanswers()->get();
        $submissions->load('user');
        //*checked the already marked ones
        $submissions = $submissions->filter(function ($submission) use ($test){
            $marked = $test->theoryresults()->where('user_id',$submission->user_id)->get();
            if(count($marked)>0){
                //*then update it
                $submission->marked = true;
            }else{
                //*else create it
                $submission->marked = false;
            }
            return true;
        })->all();

        return response()->json($submissions);
    }


    public function markdetails(Request $request, TheoryTest $test){
        $user_id = $request->user_id;
        $question = $test->theoryquestions()->first();
        $answer = $question->theoryanswers()->where('user_id',$user_id)->first();
        $test->question = $question;
        $test->answer = $answer;
        $test->user = $answer->user()->first();
        return response()->json($test);
    }
}
