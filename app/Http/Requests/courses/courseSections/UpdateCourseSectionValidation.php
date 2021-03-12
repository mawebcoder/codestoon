<?php

namespace App\Http\Requests\courses\courseSections;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseSectionValidation extends FormRequest
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
            'meta'=>['required','max:190'],
            'fa_title' => ['bail', 'required', 'max:190',Rule::unique('course_sections','fa_title')->ignore($this->route('courseSection'))],
            'en_title' => ['bail', 'nullable','max:190',Rule::unique('course_sections','en_title')->ignore($this->route('courseSection'))],
            'course_id' => ['bail', 'required', 'exists:courses,id']
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.max' => 'حداکثر کاراکترهای مجاز برای عنوان فارسی 190 کاراکتر است',
            'fa_title.unique' => 'این عنوان فارسی در سیستم وجود دارد',
            'en_title.max' => 'حداکثر کاراکترهای مجاز زبان انگلیسی 190 کاراکتر است',
            'en_title.unique' => 'این عنوان انگلیسی در سیستم وجود دارد',
            'course_id.required' => 'وارد کردن عنوان دوره الزامی است',
            'course_id.exists' => 'این دوره در سیستم وجود ندارد',
            'meta.required'=>'وارد کردن تگ متا الزامی است',
            'meta.max'=>'حداکثر کاراکترهای مجاز ۱۹۰ کاراکتر است'
        ];
    }
}
