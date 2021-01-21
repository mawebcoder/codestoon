<?php

namespace App\Http\Requests\courses\category;

use App\models\CourseCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseCategoryValidation extends FormRequest
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
            'fa_title' => ['required', Rule::unique('course_categories','fa_title')->ignore($this->route('courseCategory')), 'max:190'],
            'en_title' => [Rule::unique('course_categories','en_title')->ignore($this->route('courseCategory')), 'max:190'],
            'meta' => ['required', 'max:190'],
            'description' => ['required'],
            'file' => ['mimes:png,jpg,jpeg'],
            'parent' => [Rule::notIn([$this->route('courseCategory')]),Rule::in(array_merge([0], CourseCategory::select('id')->pluck('id')->toArray()))]
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.unique' => 'این عنوان فارسی قبلا در سیستم ثبت شده است',
            'fa_title.max' => 'حداکثر کاراکترهای مجاز برای عنوان فارسی 190 کاراکتر است',
            'en_title.unique' => 'این عنوان لاتین قبلا در سیستم ثبت شده است',
            'en_title.max' => 'حداکثر کاراکترهای مجاز برای عنوان لاتین 190 کاراکتر است',
            'meta.required' => 'وارد کردن متا الزامی است',
            'meta.max' => 'حداکثر کاراکترهای مجاز برای متا 190 کاراکتر است',
            'description.required' => 'وارد کردن توضیحات الزامی است',
            'file.mimes' => 'فرمت فابل نامعتبر است',
            'file.size' => 'سایز فایل بیشتر از 2 مگابایت است',
            'parent.in'=>'دسته والد نامعتبر است',
            'parent.not_in'=>'دسته والد نامعتبر است',
        ];
    }
}
