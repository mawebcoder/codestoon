<?php

namespace App\Http\Requests\articles;

use App\models\ArticleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticle extends FormRequest
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
            'fa_title' => ['bail','required', 'max:190','unique:article_categories,fa_title'],
            'en_title' => ['max:190'],
            'description' => ['bail','nullable', 'max:2000'],
            'file' => ['bail','max:2048', 'mimes:png,jpeg,jpg'],
            'parent' => ['bail',Rule::in(array_merge([0], ArticleCategory::select('id')->get()->pluck('id')->toArray()))]
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.max'=>'حداکثر کاراکترهای مجاز برای عنوان فارسی 190 کاراکتر است',
            'fa_title.unique'=>'این عنوان فارسی قبلا در سیستم موجود میباشد',
            'en_title.max'=>'حداکثر کاراکترهای مجاز برای عنوان انگلیسی 190 کاراکتر است',
            'description.max'=>'حداکثر کاراکترهای مجاز برای توضیحات 2000 کاراکتر است',
            'file.max'=>'حداکثر حجم عکس کاور مقاله 2 مگابایت است',
            'file.mimes'=>'فرمت های مجاز png,jpeg,jpg  می باشند',
            'parent.in'=>'مقدار وارد شده برای دسته والد نامعتبر است'
        ];
    }
}
