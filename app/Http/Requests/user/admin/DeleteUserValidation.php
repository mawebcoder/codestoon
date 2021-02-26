<?php

namespace App\Http\Requests\user\admin;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserValidation extends FormRequest
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
    public function rules():array
    {
        return [
            'ids'=>['required','array'],
            'ids.*'=>['distinct','exists:users,id']
        ];
    }
    public function messages():array
    {
        return [
            'ids.required'=>'حداقل یک مورد را انتخاب کنید',
            'ids.array'=>'داده های نامعتبر',
            'ids.*.distinct'=>'موارد مشابه انتخاب شده  است',
            'ids.*.exists'=>'داده های نامعتبر',
        ];
    }
}
