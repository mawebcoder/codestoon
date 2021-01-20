<?php

namespace App\Http\Requests\courses\category;

use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteCourseCategoryValidation extends FormRequest
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
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:course_categories,id', 'distinct']
        ];
    }

    public function messages()
    {
        return [
            'ids.required'=>'انتخاب حداقل یک مورد الزامی است',
            'ids.array'=>'داده ها نامعتبر هستند',
            'ids.*.exists'=>'داده ها نامعتبر هستند',
            'ids.*.distinct'=>'موارد مشابه انتخاب شده است'
        ];
    }
}
