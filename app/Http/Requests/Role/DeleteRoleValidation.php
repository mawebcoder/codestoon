<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRoleValidation extends FormRequest
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
            'ids.*' => ['distinct', 'exists:roles,id']
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => 'حداقل یک مورد را باید انتخاب کنید',
            'ids.array' => 'داده نامعتبر',
            'ids.*.distinct' => 'داده نامعتبر',
            'ids.*.exists' => 'داده نامعتبر'
        ];
    }
}
