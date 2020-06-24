<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            "datas.*.question.question" => 'required',
            "datas.*.options.*.option" => 'required',
            "datas.*.options.*.is_correct" => 'required'
        ];
    }

    public function messages()
    {
        return [
            "datas.*.question.question.required" => 'Fill all empty Question fields',
            "datas.*.options.*.option.required" => 'Fill all empty Options fields',
            "datas.*.options.*.is_correct.required" => 'Specify all correct options'
        ];
    }
}
