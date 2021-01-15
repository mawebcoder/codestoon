<?php

namespace App\Http\Requests\articles;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidation extends FormRequest
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
            'fa_title' => ['required', 'unique:articles,fa_title', 'max:190'],
            'en_title' => ['max:190'],
            'writer' => ['required','exists:users,id'],
            'cover_file_name' => ['max:2048', 'mimes:jpg,jpeg,png'],
            'meta' => ['required', 'max:190'],
            'articleCategory_id' => ['required', 'exists:article_categories,id']
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.max' => 'حداکثر کاراکترهای مجاز عنوان فارسی 190 کاراکتر است',
            'fa_title.unique' => 'این عنوان فارسی قبلا در سیستم ثبت شده است',
            'en_title.max' => 'حداکثر کاراکترهای عنوان انگلیسی 190 کاراکتر است',
            'cover_file_name.max' => 'حداکثر سایز عکس 2 مگابایت است',
            'cover_file_name.mimes' => 'فرمت عکس باید یکی از فرمت های jpg,png,jpeg باشد',
            'articleCategory_id.required' => 'باید حداقل یک دسته بندی انتخاب کنید',
            'articleCategory_id.exists' => 'دسته بندی انتخاب شده نامعتبر است',
            'meta.required' => 'وارد کردن توضیحات متا الزامی است',
            'meta.max' => 'حداکثر کاراکترهای مجاز برای توضیحات متا تگ 190 میباشد',
            'writer.required'=>'نویسنده این مقاله را مشخص نکرده اید',
            'writer.exists'=>'نویسنده انتخاب شده نامعتبر است'
        ];
    }
}
