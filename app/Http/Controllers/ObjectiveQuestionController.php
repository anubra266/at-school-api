<?php

namespace App\Http\Controllers;

use App\ObjectiveTest;
use Illuminate\Http\Request;
use App\Http\Requests\ExcelUploadRequest;
use App\ObjectiveQuestion;
use App\ObjectiveSolution;

class ObjectiveQuestionController extends Controller
{

    public function store(Request $request, ObjectiveTest $test){
        $data = $request->validate([
            "question.question" => 'required',
            "options.*.option" => 'required',
            "options.*.is_correct" => 'required'
        ]);
        $question = $test->objectivequestions()->create($data['question']);
        $options = $data['options'];
        $question->objectiveoptions()->createMany($options);
        event(new \App\Events\UpdateTestQuestions());
        return response()->json('Question added Successfully');
    }
    public function edit(Request $request, ObjectiveQuestion $question){
        $data = $request->validate([
            "question.question" => 'required',
            "options.*.option" => 'required',
            "options.*.is_correct" => 'required'
        ]);
        $question->update($data['question']);
        $question->objectiveoptions()->delete();
        $question->objectiveoptions()->createMany($data['options']);
        event(new \App\Events\UpdateTestQuestions());
        return response()->json('Question Modified Successfully');

    }
    public function storeexcel(ExcelUploadRequest $request, ObjectiveTest $test)
    {

        $datas = $request->validated()['datas'];

        foreach ($datas as $key => $data) {
            $the_question = $data['question'];
            $the_options = $data['options'];
            $question = $test->objectivequestions()->create($the_question);
            $question->objectiveoptions()->createMany($the_options);
        }
        event(new \App\Events\UpdateTestQuestions());
        return response()->json('Excel file Questions uploaded Successfully');
    }
    public function addsolution(Request $request, ObjectiveQuestion $question){
        $data = $request->validate(['solution'=>'required']);
        $question->objectivesolutions()->create($data);
        return response()->json(["message"=>"Solution saved successfully"]);
    }
    public function updatesolution(Request $request, ObjectiveSolution $solution){
        $data = $request->validate(["solution"=>"required"]);
        $solution->update($data);
        return response()->json(["message"=>"Solution modeified successfully"]);
    }
}
