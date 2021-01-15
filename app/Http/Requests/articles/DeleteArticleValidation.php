<?php

namespace App\Http\Requests\articles;

use Illuminate\Foundation\Http\FormRequest;

class DeleteArticleValidation extends FormRequest
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
            'ids.*' => ['distinct', 'exists:articles,id']
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => 'حداقل یک مورد باید انتخاب شود',
            'ids.array' => 'داده های ورودی معتبر نیستند',
            'ids.*.distinct' => 'موارد مشابه در بین گزینه های انتخابی مجاز نیست',
            'ids.*.exists' => 'موارد انتخاب شده نامعتبر هستند'
        ];
    }
}
