<?php

namespace App\Http\Requests\courses;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseValidation extends FormRequest
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
            'fa_title' => ['required', 'max:190', 'unique:courses,fa_title'],
            'en_title' => ['sometimes','unique:courses,en_title', 'max:190'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'discount_value' => ['nullable','min:0', 'max:100', 'numeric'],
            'level' => ['required', 'in:beginner,medium,advanced'],
            'teacher_id' => ['required', 'exists:users,id'],
            'short_description' => ['required'],
            'meta' => ['required', 'max:190'],
            'file' => ['required', 'mimes:jpg,png,jpeg', 'max:2048'],
            'courseCategory_id' => ['required', 'exists:course_categories,id'],
            'tags_ids.*' => ['sometimes','distinct', 'exists:course_tags,id']
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.unique' => 'این عنوان فارسی قبلا در سیستم وجود دارد',
            'fa_title.max' => 'حداکثر کاراکترهای مجاز برای زبان فارسی 190 کاراکتر است',
            'en_title.required' => 'وارد کردن عنوان انگلیسی الزامی است',
            'en_title.unique' => 'این عنوان انگلیسی قبلا در سیستم وجود دارد',
            'en_title.max' => 'حداکثر کاراکترهای مجاز برای عنوان انگبیسی 190 کاراکتر است',
            'description.required' => 'وارد کردن توضیحات الزامی است',
            'price.required' => 'وارد کردن قیمت الزامی است',
            'price.numeric' => 'قیمت باید عددی باشد',
            'discount_value.min' => 'مقدار کد تخفیف نامعتبر است',
            'discount_value.max' => 'مقدار کد تخفیف نامعتبر است',
            'discount_value.numeric' => 'مقدار کد تخفیف نامعتبر است',
            'level.required' => 'سطح وارد شده نامعتبر است',
            'level.in' => 'سطح وارد شده نامعتبر است',
            'teacher_id.required' => 'یک مدرس انتخاب کنید',
            'teacher_id.exists' => 'این مدرس نامعتبر است',
            'short_description.required' => 'توضیحات کوتاه را وارد کنید',
            'meta.required' => 'وارد کردن توضیحات متا الزامی است',
            'meta.max' => 'حداکثر کاراکترهای توضیحات متا 190 کاراکتر است',
            'courseCategory_id.required' => 'دسته بندی این دوره را انتخاب نکرده اید',
            'courseCategory_id.exists' => 'دسته بندی انتخاب شده نامعتبر است',
            'tag_ids.*.distinct' => 'تگ های انتخاب شده نامعتبر است',
            'tag_ids.*.exists' => 'تگ های انتخاب شده نامعتبر است',
            'file.required'=>'هیچ عکسی را انتخاب نکرده اید',
            'file.max'=>'سایز عکس از ۲ مگابایت نمیتواند بیشتر باشد',
            'file.mimes'=>'فرمت فایل نامعتبر است'
        ];
    }
}
