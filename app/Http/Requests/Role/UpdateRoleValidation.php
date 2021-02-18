<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleValidation extends FormRequest
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
            'name' => ['required', 'max:190' ,Rule::unique('roles', 'name')->ignore(request()->route('role'))],
            'fa_name'=>['required','max:190' ,Rule::unique('roles','fa_name')->ignore(request()->route('role'))]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'وارد کردن عنوان انگلیسی نقش الزامی است',
            'name.unique' => 'نقش با چنین عنوانی به انگلیسی قبلا در سیستم ثبت شده است',
            'fa_name.required'=>'وارد کردن عنوان نقش به فارسی الزامی است',
            'fa_name.unique'=>'نقش با چنین عنوانی به فارسی قبلا در سیستم ثبت شده است',
            'fa_name.max'=>'حداکثر کاراکترهای مجاز برای عنوان فارسی 190 کاراکتر است',
            'name.max'=>'حداکثر کاراکترهای مجاز برای عنوان انگلیسی 190 کاراکتر است'
        ];
    }
}
