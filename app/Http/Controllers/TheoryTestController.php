<?php

namespace App\Http\Controllers;

use App\Classroom;
use Carbon\Carbon;
use App\TheoryTest;
use App\TheoryAnswer;
use App\TheoryQuestion;
use App\TheorySolution;
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
            ->where('starttime', "<=", $now)
        ->get();
        //*Show only tests that has not been marked and have questions set.
        $tests = $tests->filter(function ($test) {
            $marked = $test->theoryresults()->where('user_id', auth()->user()->id)->get();
            $question = $test->theoryquestions()->first();

            if (count($marked) > 0 || $question === null) {
                return false;
            } else {
                return true;
            }
        })->values()->all();
        return response()->json($tests);
    }
    public function solutions(Request $request)
    {
        $now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addHour())->format('Y-m-d H:i:s');
        $classroom = $this->checkclassroom($request);
        $tests = $classroom->theorytests()
            ->get();

        //*Show only tests that have questions, have been marked and have solutions.
        $tests = $tests->filter(function ($test) {
            $marked = $test->theoryresults()->where('user_id', auth()->user()->id)->get();
            $question = $test->theoryquestions()->first();
            $solution = $test->theorysolution()->first();

            if (count($marked) > 0 && $solution !== null && $question !== null) {
                $answer = $question->theoryanswers()->where('user_id', auth()->user()->id)->first();
                $test->result = $marked->first();
                $test->answer = $answer;
                $test->question = $question;
                $test->solution = $solution;
                return true;
            } else {
                return false;
            }
        })->values()->all();

        return response()->json($tests);
    }
    public function mark(Request $request)
    {
        $classroom = $this->checkclassroom($request);

        $tests = $classroom->theorytests()->get();
        foreach ($tests as $test) {
            $markings = $test->theoryresults()->get();
            $marked = count($markings) > 0;
            $test->marked = $marked;
        }
        $tests = collect($tests);
        $tests = $tests->sort(function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a->created_at > $b->created_at) ? -1 : 1;
        })->values()->all();

        return response()->json($tests);
    }
    public function results(Request $request)
    {
        $classroom = $this->checkclassroom($request);

        $tests = $classroom->theorytests()->get();
        $tests = $tests->filter(function ($test) {
            $theoryresults = $test->theoryresults()->where('user_id', auth()->user()->id)->get();
            $test->theoryresult = $theoryresults->first();
            $theoryquestion = $test->theoryquestions()->first();
            $theoryanswer = $theoryquestion->theoryanswers()->where('user_id', auth()->user()->id)->first();
            $test->submitted = $theoryanswer;
            return (count($theoryresults) > 0) ? true : false;
        })->values()->all();
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
            'starttime' => 'required',
            'total' => 'required'
        ]);
        $classroom = $getclassroom;
        $test = $classroom->theorytests()->create($data);
        return response()->json($test);
    }
    public function show(TheoryTest $test)
    {

        $question = $test->theoryquestions()->first();
        $test['theoryquestion'] = $question;
        $test->theoryquestion['theoryanswer'] = $question->theoryanswers()->where('user_id', auth()->user()->id)->get();
        return response()->json($test);
    }
    public function showdetails(TheoryTest $test)
    {
        $test->load('theoryquestions');
        $test->load('theorysolution');
        return response()->json($test);
    }
    public function submissions(TheoryTest $test)
    {
        $question = $test->theoryquestions()->first();

        if ($question === null) {
            return response()->json(['message' => "There are no Set Questions, You should add some"], 400);
        }
        $submissions = $question->theoryanswers()->get();
        $submissions->load('user');
        //*checked the already marked ones
        $submissions = $submissions->filter(function ($submission) use ($test) {
            $marked = $test->theoryresults()->where('user_id', $submission->user_id)->get();
            if (count($marked) > 0) {
                //*then update it
                $submission->marked = true;
            } else {
                //*else create it
                $submission->marked = false;
            }
            return true;
        })->all();

        return response()->json($submissions);
    }


    public function markdetails(Request $request, TheoryTest $test)
    {
        $user_id = $request->user_id;
        $question = $test->theoryquestions()->first();
        $answer = $question->theoryanswers()->where('user_id', $user_id)->first();
        $test->question = $question;
        $test->answer = $answer;
        $test->user = $answer->user()->first();
        $marked = $test->theoryresults()->where('user_id', $test->user->id)->get();
        if (count($marked) > 0) {
            //*then update it
            $test->score = $marked->first()->score;
        }
        return response()->json($test);
    }
    public function getresults(TheoryTest $test)
    {
        $classroom = Classroom::where('id', $test->classroom_id)->first();

        $results = $test->theoryresults()->get();
        $results->load('user');

        return response()->json([$results, $test]);
    }

    public function addsolution(Request $request, TheoryTest $test)
    {
        $data = $request->validate(['solution' => 'required']);
        $solution = $test->theorysolution()->create($data);
        event(new \App\Events\UpdateSolutions());
        return response()->json($solution);
    }

    public function updatesolution(Request $request, TheorySolution $solution)
    {
        $data = $request->validate(['solution' => 'required']);
        $solution->update($data);
        event(new \App\Events\UpdateSolutions());
        return response()->json(["message" => "Update Successful"], 200);
    }
}
