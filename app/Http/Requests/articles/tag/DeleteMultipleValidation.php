<?php

namespace App\Http\Requests\articles\tag;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMultipleValidation extends FormRequest
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
            'ids.*' => ['distinct', 'exists:article_tags,id']
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => 'حداقل یک مورد را باید انتخاب کنید',
            'ids.array' => 'نوع داده معتبر نیست',
            'ids.*.distinct' => 'داده های مشابه به سرور ارسال شده اند',
            'ids.*.exists' => 'داده های نامعتبر وارد شده اند'
        ];
    }
}
