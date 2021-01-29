<?php

namespace App\Http\Requests\comment;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array(request()->type, ['video', 'course', 'article']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $valid_parent_values = array_merge([0], User::query()->select('id')->pluck('id')->toArray());

        switch (request()->type) {
            case ('video'):
                $arr = [
                    'text' => ['required'],
                    'parent' => [Rule::in($valid_parent_values)],
                    'type' => ['required', Rule::in(['video', 'article', 'course'])],
                    'commentable_id' => ['required','exists:videos,id'],
                ];
                break;
            case ('article'):
                $arr = [
                    'text' => ['required'],
                    'parent' => [Rule::in($valid_parent_values)],
                    'type' => ['required', Rule::in(['video', 'article', 'course'])],
                    'commentable_id' => ['required','exists:articles,id'],
                ];
                break;
            default:
                $arr = [
                    'text' => ['required'],
                    'parent' => [Rule::in($valid_parent_values)],
                    'type' => ['required', Rule::in(['video', 'article', 'course'])],
                    'commentable_id' => ['required','exists:courses,id'],
                ];
                break;
        }
        return $arr;
    }
}
