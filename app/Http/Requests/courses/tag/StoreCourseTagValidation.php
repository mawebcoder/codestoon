<?php

namespace App\Http\Requests\courses\tag;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseTagValidation extends FormRequest
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
            'fa_title'=>['required','unique:course_tags,fa_title','max:190'],
            'en_title'=>['required','unique:course_tags,en_title','max:190']
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
