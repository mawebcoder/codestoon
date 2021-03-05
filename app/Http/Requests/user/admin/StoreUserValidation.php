<?php

namespace App\Http\Requests\user\admin;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class StoreUserValidation extends FormRequest
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
        $validation = [];

        $role_id = request()->role_id;

        $role = Role::query()->find($role_id);

        //if there is no any role
        if (!$role) {
            $validation = array_merge($validation, [
                'role_id' => ['required', 'exists:roles,id'],
            ]);
//           if the user is the user
        } elseif ($role->name !== 'teacher') {

            $validation = array_merge($validation, [
                'name' => ['required', 'max:190'],
                'family' => ['required', 'max:190'],
                'cell' => ['required', 'unique:users,cell', 'regex:/09[0-3][0-9][0-9]{7}$/'],
                'password' => ['required', 'min:9', 'max:190'],
                'confirm_password' => ['required', 'same:password'],
                'confirm_email' => ['required', 'same:email'],
                'confirm_cell' => ['required', 'same:cell'],
                'email' => ['required', 'email', 'unique:users,email'],
                'role_id' => ['required', 'exists:roles,id'],
                'file' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],


            ]);

//          if the user is a teacher
        } elseif ($role->name == 'teacher') {

            $validation = array_merge($validation, [
                'name' => ['required', 'max:190'],
                'family' => ['required', 'max:190'],
                'cell' => ['required', 'unique:users,cell', 'regex:/09[0-3][0-9][0-9]{7}$/'],
                'password' => ['required', 'min:9', 'max:190'],
                'confirm_password' => ['required', 'same:password'],
                'email' => ['required', 'email', 'unique:users,email'],
                'role_id' => ['required', 'exists:roles,id'],
                'confirm_email' => ['required', 'same:email'],
                'confirm_cell' => ['required', 'same:cell'],
                'file' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],
                'resume_pdf_file' => ['required', 'mimes:pdf', 'max:1024'],
                'back_nationality_card_image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
                'front_nationality_card_image' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
                'description' => ['required'],
                'address' => ['required'],
                'nationality_code' => ['required', 'unique:users,nationality_code'],
            ]);
        }
        return $validation;
    }

    public function messages()
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
            'resume_pdf_file.required' => 'وارد کردن فایل رزومه الزامی است',
            'resume_pdf_file.mimes' => 'فرمت فایل رزومه باید پی در اف باشد',
            'resume_pdf_file.max' => 'حداکثر سایز فایل pdf یگ مگابایت است',
            'back_nationality_card_image.required' => 'عکس کارت ملی از پشت را وارد نکرده اید',
            'back_nationality_card_image.mimes' => 'عکس کارت ملی از پشت فرمت نامعتبری دارد',
            'back_nationality_card_image.max' => 'عکس کارت ملی از پشت حداکثر سایز ۲ مگابایت میتواند باشد',
            'front_nationality_card_image.required' => 'عکس کارت ملی از جلو را وارد نکرده اید',
            'front_nationality_card_image.mimes' => 'عکس کارت ملی از جلو فرمت نامعتبری دارد',
            'front_nationality_card_image.max' => 'عکس کارت ملی از جلو حداکثر سایز ۲ مگابایت میتواند باشد',
            'description.required' => 'توضیحات را وارد نکرده اید',
            'address.required' => 'آدرس را وارد کنید',
            'nationality_code.required' => 'کد ملی را وارد کنید',
            'nationality_code.unique' => 'کد ملی را وارد کنید',
            'confirm_email.required'=>'وارد کردن تکرار ایمیل الزامی است',
            'confirm_email.same'=>'ایمیل با تکرار آن برابر نیست',
            'confirm_cell.required'=>'وارد کردن تکرار شماره موبایل الزامی است',
            'confirm_cell.same'=>'شماره موبایل با تکرار آن برابر نیست'
        ];
    }
}
