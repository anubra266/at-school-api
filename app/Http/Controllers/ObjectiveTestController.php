<?php

namespace App\Http\Controllers;

use App\Cbt;
use App\Classroom;
use Carbon\Carbon;
use App\ObjectiveTest;
use Illuminate\Http\Request;

class ObjectiveTestController extends Controller
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
        $tests = $classroom->objectivetests()
            ->whereDate('deadline', ">=", Carbon::today()->toDateString())
            ->whereDate('starttime', "<=", Carbon::today()->toDateString())
            ->get();
            $tests->load('objectivequestions');
        $tests->load('cbts');
        $tests_not_done = $tests->reject(function($test,$index){
            $cbts = $test->cbts->pluck('user_id');
            return $cbts->contains(auth()->user()->id);
        });
        $tests = $tests_not_done->all();
        return response()->json($tests);
    }
    public function store(Request $request)
    {
        $getclassroom = $this->checkclassroom($request);
        $data = $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'starttime' => 'required',
            'deadline' => 'required',
        ]);
        $classroom = $getclassroom;
        $test = $classroom->objectivetests()->create($data);
        return response()->json($test);
    }
    public function show(ObjectiveTest $test)
    {
        $test->load('objectivequestions.objectiveoptions');
        $questions = $test['objectivequestions'];
        for ($x = 0; $x < count($questions); $x++) {
            $question = $questions[$x];
            $options = $question['objectiveoptions'];
            for ($y = 0; $y < count($options); $y++) {
                $option = $options[$y];
                $option['is_correct'] = null;
            }
        }
        return response()->json($test);
    }
    public function showreview(ObjectiveTest $test)
    {
        $test->load('objectivequestions.objectiveoptions');
        $questions = $test['objectivequestions'];
        return response()->json($test);
    }
    public function showresult(ObjectiveTest $test)
    {
        $cbts = $test->cbts()->get();
        $user_cbt = $cbts->where('user_id', auth()->user()->id)->first();
        $user_answers = $user_cbt->objectiveanswers()->get();
        return response()->json($user_answers);
    }

    public function submit(Request $request, ObjectiveTest $test)
    {
        //* check if test has been taken before
        $bef = $test->cbts()->where('user_id', auth()->user()->id)->first();
        if ($bef === null) {
            // //?Check if the time (created at) is within 5 secs of submission, and say nothing
            // $former_date = $bef->created_at;
            // $to = Carbon::createFromFormat('Y-m-d H:s:i', $former_date);
            // $from = Carbon::createFromFormat('Y-m-d H:s:i', now());
            // $date_diff = $to->diffInSeconds($from);
            // if ($date_diff < (6)) {
            //     return response()->json([true],200);
            // }
            //?if not, give the error
            //return response()->json('nothing');
            //store cbt record  database 
            $cbt_details = [];
            $cbt_details['user_id'] = auth()->user()->id;
            $savecbt = $test->cbts()->create($cbt_details);

            $cbt = $request->cbt;
            //*array of correct answers
            $correct = [];
            $wrong = [];
            //*looping throught the test results
            for ($x = 0; $x < count($cbt); $x++) {
                $result = $cbt[$x];
                //*get the question id, since it's the key
                $question = array_keys($result)[0];
                $answer = $result[$question];
                //*questions from database
                $questions = $test->objectivequestions()->get();
                //*get the question for the present result
                $db_question = $questions->where('id', $question)->first();
                //*get the options for the question
                $options = $db_question->objectiveoptions()->get();
                //*correct option
                $db_answer = $options->where('is_correct', 1)->first()->id;
                //*check if the answer is same as the chosen option
                if ($answer === $db_answer) {
                    array_push($correct, $db_question->id);
                } else {
                    array_push($wrong, $db_question->id);
                }

                //?store the answers in the database
                $answer_details = [];
                $answer_details['cbt_id'] = $savecbt->id;
                $answer_details['option_id'] = $answer;
                $db_question->objectiveanswers()->create($answer_details);
            }

            $score =  count($correct);
            $total = count($correct) + count($wrong);
            $cbt_update = [];
            $cbt_update['score'] = $score;
            $cbt_update['total'] = $total;
            Cbt::where('id', $savecbt->id)->update($cbt_update);

            return response()->json([$score, $total]);
        }
    }
}