<?php

namespace App\Http\Requests\video\tag;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoTagValidation extends FormRequest
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
            'fa_title'=>['required','unique:video_tags,fa_title'],
            'en_title'=>['required','unique:video_tags,en_title'],
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required'=>'وارد کردن عنوان فارسی الزامی است',
            'en_title.unique'=>'این عنوان انگلیسی قبلا ایجاد شده است',
            'en_title.required'=>'وارد کردن عنوان انگلیسی الزامی است',
            'fa_title.unique'=>'عنوان فارسی با چنین نامی وجود دارد',
        ];
    }
}
