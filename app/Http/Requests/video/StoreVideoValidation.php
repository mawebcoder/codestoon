<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoValidation extends FormRequest
{
    /**
     * Determinutee if the user is authorized to make this request.
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
            'minute' => ['required','numeric', 'integer', 'max:45'],
            'second' => ['required','numeric', 'integer', 'max:59'],
            'courseSection_id' => ['sometimes','nullable','exists:course_sections,id'],
            'course_id' => ['sometimes','nullable','exists:courses,id'],
            'meta' => ['required', 'max:190'],
            'video_tag_ids'=>['required','array'],
            'video_tag_ids.*'=>['distinct','exists:video_tags,id']
        ];
    }

    public function messages()
    {
        return [
            'fa_title.required' => 'وارد کردن عنوان فارسی الزامی است',
            'fa_title.max' => 'تعداد کاراکترهای عنوان فارسی از 190 کاراکتر نمیتواند بیشتر باشد',
            'en_title.max' => 'تعداد کاراکترهای عنوان انگلیسی از 190 کاراکتر نمیتواند بیشتر باشد',
            'description.required' => 'توضیحات را وارد کنید',
            'minute.numeric' => 'فرمت دقیقه نادرست است',
            'minute.integer' => 'فرمت دقیقه نادرست است',
            'minute.max' => 'ویدیو بیشتر از ۴۵ دقیقه نمیتواند باشد',
            'minute.required' => 'وارد کردن دقیقه الزامی است',
            'sec.numeric' => 'فرمت ثانبه نادرست است',
            'second.integer' => 'فرمت ثانبه نادرست است',
            'second.max' => 'ثانیه بیشتر از ۵۹ نمیتواند باشد',
            'courseSection_id.exists' => 'فصل انتخاب شده نامعتبر است',
            'course_id.exists' => 'دوره انتخاب شده نامعتبر است',
            'meta.required' => 'وارد کردن توضیحات متا الزامی است',
            'meta.max' => 'حداکثر کاراکترها برای توضیحات متا 190 کاراکتر است',
            'video_tag_ids.required'=>'وارد کردن تگ ویدیو الزامی است',
            'video_tag_ids.array'=>'فرمت تگ ویدیو نامعتبر است',
            'video_tag_ids.distinct'=>'تگ های ویدیو نامعبر هستند',
            'video_tag_ids.exists'=>'تگ های ویدیو نامعتبر هستند'
        ];
    }
}
