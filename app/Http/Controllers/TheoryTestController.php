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

        $classroom = $this->checkclassroom($request);
        $tests = $classroom->theorytests()
            ->whereDate('deadline', ">=", Carbon::today()->toDateString())
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
        $classroom = Classroom::where('id', $test->classroom_id)->first();
        if ($classroom->user_id == auth()->user()->id) {
            $test->load('theoryquestions');
            return response()->json($test);
        } else {
            // return response()->json([auth()->user()->id]);
            return response()->json(["message" => 'You don\'t have the permission!'], 403);
        }
    }
}
