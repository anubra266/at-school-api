<?php

namespace App\Http\Controllers;

use App\ObjectiveTest;
use Illuminate\Http\Request;
use App\Http\Requests\ExcelUploadRequest;

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
        return response()->json('Question added Successfully');
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
        return response()->json('Excel file Questions uploaded Successfully');
    }
}
