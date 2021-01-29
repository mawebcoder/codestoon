<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;

class UploadVideoValidation extends FormRequest
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
            'file'=>['required','mimes:mp4']
        ];
    }
    public function messages()
    {
        return [
            'file.mimes'=>'فرمت ویدیو نامناسب است',
            'file.required'=>'ویدیو مورد نظر را آپلود کنید'
        ];
    }
}
