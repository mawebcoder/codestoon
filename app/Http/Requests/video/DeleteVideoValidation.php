<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;

class DeleteVideoValidation extends FormRequest
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
            'ids.*'=>['distinct','exists:videos,id']
        ];
    }
    public function messages()
    {
        return [
            'ids.required'=>'هیچ موردی انتخاب نشده است',
            'ids.array'=>'داده نامعتبر',
            'ids.*.distinct'=>'موارد مشابه انتخاب شده است',
            'ids.*.exists'=>'داده نامعتبر است'
        ];
    }
}
