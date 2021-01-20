<?php

namespace App\Http\Requests\courses\tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseTagValidation extends FormRequest
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
            'fa_title'=>['required',Rule::unique('course_tags','fa_title')->ignore($this->route('courseTag')),'max:190'],
            'en_title'=>['required',Rule::unique('course_tags','en_title')->ignore($this->route('courseTag')),'max:190']
        ];
    }
    public function messages()
    {
        return [
            'fa_title.required'=>'وارد کردن عنوان فارسی الزامی است',
            'fa_title.unique'=>'این نام فارسی قبلا وجود دارد',
            'fa_title.max'=>'حداکثر کاراکترهای مجاز برای عنوان فارسی 190 کاراکتر است',
            'en_title.required'=>'وارد کردن عنوان انگلیسی الزامی است',
            'en_title.unique'=>'این عنوان انگلیسی قبلا وجود دارد',
            'en_title.max'=>'حداکثر کاراکترهای مجاز برای عنوان انگلیسی 190 کاراکتر است',
        ];
    }
}
