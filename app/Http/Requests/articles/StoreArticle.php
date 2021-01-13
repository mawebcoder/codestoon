<?php

namespace App\Http\Requests\articles;

use App\models\ArticleCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticle extends FormRequest
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
            'fa_title' => ['required', 'max:190', 'string', 'unique:article_categories,fa_title'],
            'en_title' => ['string', 'max:190'],
            'description' => ['nullable', 'max:2000'],
            'file' => ['max:2048', 'mimes:png,jpeg,jpg'],
            'parent' => ['integer', Rule::in(array_merge([0], ArticleCategory::select('id')->get()->pluck('id')->toArray()))]
        ];
    }
}
