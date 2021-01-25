<?php

namespace App\Http\Requests\video\tag;

use Illuminate\Foundation\Http\FormRequest;

class DeleteVideoTagValidation extends FormRequest
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
            'ids' => ['required', 'array'],
            'ids.*' => ['distinct', 'exists:video_tags,id']
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => 'حداقل یک مورد را انتخاب کنید',
            'ids.array' => 'ورودی نامعتبر است',
            'ids.*.distinct' => 'موارد مشابه انتخاب شده اند',
            'ids.*.exists' => 'داده های نامعتبر وارد شده است',
        ];
    }
}
