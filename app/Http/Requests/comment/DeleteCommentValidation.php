<?php

namespace App\Http\Requests\comment;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCommentValidation extends FormRequest
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
            'ids'=>['required','array'],
            'ids.*'=>['distinct','exists:comments,id']
        ];
    }
    public function messages()
    {
        return [
            'ids.required'=>'حداقل یک مورد را انتخاب کنید',
            'ids.array'=>'داده نامعتبر است',
            'ids.*.distinct'=>'داده های مشابه وارد شده است',
            'ids.*.exists'=>'داده نامعتبر است',
        ];
    }
}
