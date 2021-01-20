<?php

namespace App\Http\Requests\courses\tag;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCourseTagValidation extends FormRequest
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
            'ids'=>['required','array'],
            'ids.*'=>['distinct','exists:course_tags,id']
        ];
    }
    public function messages()
    {
        return [
          'ids.required'=>'هیج موردی انتخاب نشده است',
          'ids.array'=>'موارد انتخاب شده نامعتبر هستند',
          'ids.*.exists'=>'موارد انتخاب شده نامعتبر هستند',
          'ids.*.distinct'=>'موارد تکراری وجود دارد'
        ];
    }
}
