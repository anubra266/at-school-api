<?php

namespace App\Http\Controllers;

use App\Classroom;
use Carbon\Carbon;
use App\TheoryTest;
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

    public function store(Request $request)
    {
        $getclassroom = $this->checkclassroom($request);
        $data = $request->validate([
            'title' => 'required',
            'deadline' => 'required',
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
}
