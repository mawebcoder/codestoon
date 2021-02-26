<?php

namespace App\Http\Requests\user\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserValidation extends FormRequest
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
    public function rules(): array
    {
        $user=request()->route('user');

         return [
            'name' => ['required', 'max:190'],
            'family' => ['required', 'max:190'],
            'cell' => ['required', Rule::unique('users','cell')->ignore($user), 'regex:/09[0-3][0-9][0-9]{7}$/'],
            'password' => ['required', 'min:9', 'max:190'],
            'confirm_password' => ['required', 'same:password'],
            'email' => ['required', 'email',Rule::unique('users','email')->ignore($user)],
            'role_id' => ['required', 'exists:roles,id'],
            'file' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'وارد کردن نام کاربر الزامی است',
            'name.max' => 'حداکثر کاراکترهای برای نام ۱۹۰ کاراکتر است',
            'family.required' => 'وارد کردن خانوادگی کاربر الزامی است',
            'family.max' => 'حداکثر کاراکترهای نام خانوادگی ۱۹۰ کاراکتر است',
            'cell.required' => 'وارد کردن شماره تلفن الزامی است',
            'cell.unique' => 'این شماره تلفن قبلا در سیستم ثب شده است',
            'cell.regex' => 'فرمت شماره تلفن نامعتبر است',
            'password.required' => 'وارد کردن رمز عبور الزامی است',
            'password.min' => 'حداقل کاراکتر های مجاز برای رمز عبور ۸ کاراکتر است',
            'password.max' => 'حداکثر کاراکترهای رمز عبور ۱۹۰ کاراکتر است',
            'confirm_password.required' => 'وارد کردن تکرار رمز عبور الزامی است',
            'confirm_password.same' => 'رمز عبور با تکرار آن باید برابر باشد',
            'email.required' => 'وارد کردن ایمیل الزامی است',
            'email.email' => 'فرمت ایمیل نادرست است',
            'email.unique' => 'این ایمیل قبلا در سیستم ثبت شده است',
            'role_id.required' => 'وارد کردن نقش کاربر الزامی است',
            'role_id.exists' => 'نقش انتخابی نامعتبر است',
            'file.mimes' => ' پسوند فایل انتخابی نامعتبر است',
            'file.max' => 'حداکثر سایز برای فایل ۲ مگابایت می باشد',
        ];
    }
}
