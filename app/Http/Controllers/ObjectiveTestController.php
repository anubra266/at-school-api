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
        $now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addHour())->format('Y-m-d H:i:s');
        $classroom = $this->checkclassroom($request);
        $tests = $classroom->objectivetests()
            ->where('deadline', ">", $now)
            ->where('starttime', "<=", $now)
            ->get();
        $tests->load('objectivequestions');
        $tests->load('cbts');
        // //*filter done tests
        $tests = $tests->filter(function ($test, $index) {
            $cbts = $test->cbts->pluck('user_id');
            $done = $cbts->contains(auth()->user()->id);
            return !$done;
        })->values()->all();

        return response()->json($tests);
    }
    public function solutions(Request $request)
    {
        $now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addHour())->format('Y-m-d H:i:s');
        $classroom = $this->checkclassroom($request);
        $tests = $classroom->objectivetests()->get();
        $tests->load('objectivequestions.objectivesolutions');
        $tests->load('cbts');
        $solutionCount = 0;
        //* Show tests that have been done, and have solutions for some questions
        $tests = $tests->filter(function ($test) use (&$solutionCount) {
            $cbts = $test->cbts->pluck('user_id');
            $done = $cbts->contains(auth()->user()->id);
            if ($done) {
                $hasSolutions = false;
                //*check if it has solutions
                $questions = $test->objectivequestions()->get();
                foreach ($questions as $key => $question) {
                    //*if it has solutions
                    if (count($question->objectivesolutions) > 0) {
                        $hasSolutions = true;
                        $solutionCount += 1;
                        $test->newquestions = $test->objectivequestions;
                    }else{
                        //*Add the filtered questions
                        $newquestions = $test->objectivequestions->forget($key);
                        $test->newquestions = $newquestions->values()->all();
                    }
                }

                if ($hasSolutions) {
                    return true;
                }
                return false;
            }
            return false;
        })->values()->all();
        foreach ($tests as $test) {
            $test->taken = $test->cbts()->where('user_id', auth()->user()->id)->first()->created_at;
            $test->solutions = $solutionCount;
        }
        return response()->json($tests);
    }

    public function mark(Request $request)
    {
        $classroom = $this->checkclassroom($request);
        $tests = $classroom->objectivetests()->get();
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

        $tests = $classroom->objectivetests()->get();
        $tests = $tests->filter(function ($test) {
            $objectiveresults = $test->cbts()->where('user_id', auth()->user()->id)->get();
            $test->cbtresult = $objectiveresults->first();
            $objectivequestion = $test->objectivequestions()->first();
            return (count($objectiveresults) > 0) ? true : false;
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
        $classroom = Classroom::where('id', $test->classroom_id)->first();
        $educator = $classroom->user_id;
        $test->load('objectivequestions.objectiveoptions');
        $test->load('objectivequestions.objectivesolutions');
        $test->solution;
        $questions = $test['objectivequestions'];

        for ($x = 0; $x < count($questions); $x++) {
            $question = $questions[$x];
            $options = $question['objectiveoptions'];
            for ($y = 0; $y < count($options); $y++) {
                $option = $options[$y];
                if ($educator !== auth()->user()->id) {

                    $option['is_correct'] = null;
                }
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
            event(new \App\Events\UpdateObjectiveTests());

            return response()->json([$score, $total]);
        }
    }

    public function getresults(ObjectiveTest $test)
    {
        $classroom = Classroom::where('id', $test->classroom_id)->first();

        $results = $test->cbts()->get();
        $results->load('user');
        $results = $results->filter(function ($result) {
            return ($result->user->id !== auth()->user()->id);
        })->values()->all();
        return response()->json([$results, $test]);
    }
}
