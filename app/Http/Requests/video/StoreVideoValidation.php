<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoValidation extends FormRequest
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
            'fa_title' => ['required', 'max:190'],
            'en_title' => ['max:190'],
            'description' => ['required'],
            'hour' => ['numeric', 'integer'],
            'min' => ['numeric', 'integer', 'max:59'],
            'sec' => ['numeric', 'integer', 'max:59'],
            'courseSection_id' => ['exists:course_sections,id'],
            'course_id' => ['exists:courses,id'],
            'meta' => ['required', 'max:190'],
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.max' => 'تعداد کاراکترهای عنوان فارسی از 190 کاراکتر نمیتواند بیشتر باشد',
            'en_title.max' => 'تعداد کاراکترهای عنوان انگلیسی از 190 کاراکتر نمیتواند بیشتر باشد',
            'description.required' => 'توضیحات را وارد کنید',
            'hour.numeric' => 'فرمت ساعت نادرست است',
            'hour.integer' => 'فرمت ساعت نادرست است',
            'min.numeric' => 'فرمت دقیقه نادرست است',
            'min.integer' => 'فرمت دقیقه نادرست است',
            'min.max' => 'فرمت دقیقه نادرست است',
            'sec.numeric' => 'فرمت ثانبه نادرست است',
            'sec.integer' => 'فرمت ثانبه نادرست است',
            'sec.max' => 'فرمت ثانبه نادرست است',
            'courseSection_id.exists' => 'فصل انتخاب شده نامعتبر است',
            'course_id.exists' => 'دوره انتخاب شده نامعتبر است',
            'meta.required' => 'وارد کردن توضیحات متا الزامی است',
            'meta.max' => 'حداکثر کاراکترها برای توضیحات متا 190 کاراکتر است'
        ];
    }
}
