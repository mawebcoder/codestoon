<?php

namespace App\Http\Requests\video\tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVideoTagValidation extends FormRequest
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
            'fa_title'=>['required',Rule::unique('video_tags','fa_title')->ignore($this->route('videoTag'))],
            'en_title'=>['required',Rule::unique('video_tags','en_title')->ignore($this->route('videoTag'))],
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
