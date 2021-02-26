<?php

namespace App\Http\Requests\user\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateTeacherValidation extends FormRequest
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
        $user_id=request()->route('user');

        return [
            'name' => ['required', 'max:190'],
            'family' => ['required', 'max:190'],
            'cell' => ['required',Rule::unique('users','cell')->ignore($user_id),'regex:/09[0-3][0-9][0-9]{7}$/'],
            'password' => ['required', 'min:9', 'max:190'],
            'confirm_password' => ['required', 'same:password'],
            'email' => ['required', 'email', Rule::unique('users','email')->ignore($user_id)],
            'role_id' => ['required', 'exists:roles,id'],
            'file' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],
            'resume_pdf_file' => ['required','mimes:pdf','max:1024'],
            'back_nationality_card_image' => ['required','mimes:jpg,jpeg,png','max:2048'],
            'front_nationality_card_image' => ['required','mimes:jpg,jpeg,png','max:2048'],
            'description'=>['required'],
            'address'=>['required'],
            'nationality_code'=>['required',Rule::unique('users','nationality_code')->ignore($user_id)],
        ];
    }

    public function messages():array
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
            'resume_pdf_file.required'=>'وارد کردن فایل رزومه الزامی است',
            'resume_pdf_file.mimes'=>'فرمت فایل رزومه باید پی در اف باشد',
            'resume_pdf_file.max'=>'حداکثر سایز فایل pdf یگ مگابایت است',
            'back_nationality_card_image.required'=>'عکس کارت ملی از پشت را وارد نکرده اید',
            'back_nationality_card_image.mimes'=>'عکس کارت ملی از پشت فرمت نامعتبری دارد',
            'back_nationality_card_image.max'=>'عکس کارت ملی از پشت حداکثر سایز ۲ مگابایت میتواند باشد',
            'front_nationality_card_image.required'=>'عکس کارت ملی از جلو را وارد نکرده اید',
            'front_nationality_card_image.mimes'=>'عکس کارت ملی از جلو فرمت نامعتبری دارد',
            'front_nationality_card_image.max'=>'عکس کارت ملی از جلو حداکثر سایز ۲ مگابایت میتواند باشد',
            'description.required'=>'توضیحات را وارد نکرده اید',
            'address.required'=>'آدرس را وارد کنید',
            'nationality_code.required'=>'کد ملی را وارد کنید'
        ];
    }

}
