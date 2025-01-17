<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'slug' => 'unique:support_articles,slug,' . $this->id,
            'title' => 'required|regex:/^[\pL\s\-]+$/u',
            'description' => 'required',
            'belongsToType' => 'required',
            'categories' => 'required'
        ];
    }
}
