<?php

namespace App\Http\Requests\courses\courseSections;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCourseSectionValidation extends FormRequest
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
            'ids'=>['bail','required','array'],
            'ids.*'=>['bail','distinct','exists:course_sections,id']
        ];
    }
    public function messages()
    {
        return [
          'ids.required'=>'حداقل یک مورد را انتخاب کنید',
          'ids.array'=>'داده نامعتبر است',
          'ids.*.distinct'=>'موارد مشابه وجود دارد',
          'ids.*.exists'=>'داده نامعتبر است',
        ];
    }
}
