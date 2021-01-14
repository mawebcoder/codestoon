<?php

namespace App\Http\Requests\articles\category;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMultiple extends FormRequest
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
            'ids.*' => ['distinct', 'exists:article_categories,id']
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => 'حداقل یک مورد را باید انتخاب کنید',
            'ids.array'=>'داده ها باید به صورت آرایه ارسال شوند',
            'ids.*.distinct' => 'موارد تکراری وجود دارد',
            'ids.*.exists' => 'موارد انتخاب شده نامعتبر است'
        ];
    }
}
