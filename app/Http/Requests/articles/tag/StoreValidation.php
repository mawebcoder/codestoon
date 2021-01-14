<?php

namespace App\Http\Requests\articles\tag;

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
            'fa_title' => ['required', 'unique:article_tags,fa_title', 'max:190'],
            'en_title' => ['required', 'unique:article_tags,en_title', 'max:190'],
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.unique' => 'این عنوان فارسی در سیستم ثبت شده است',
            'fa_title.max' => 'حداکثر کاراکترهای مجاز به فارسی 190 کاراکتر است',
            'en_title.required' => 'وارد کردن عنوان انگلیسی الزامی است',
            'en_title.unique' => 'این عنوان انگلیسی قبلا وارد شده است',
            'en_title.max' => 'حداکثر کاراکترهای مجاز در انگلیسی 190 کاراکتر است',
        ];
    }
}
