<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
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
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = \request()->input('id') ?? '';

        $rules = [
            'language_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'image' => 'required',
            'intro_short' => 'required',
            'status' => 'required',
            'meta_title' => 'required'
        ];
        if($id){
            unset($rules['image']);
        }
        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'language_id.required' => __('validation.required', ['attribute' => __('article.language')]),
            'category_id.required' => __('validation.required', ['attribute' => __('article.category')]),
            'title.required' => __('validation.required', ['attribute' => __('article.title')]),
            'content.required' => __('validation.required', ['attribute' => __('article.content')]),
            'image.required' => __('validation.required', ['attribute' => __('article.image')]),
            'intro_short.required' => __('validation.required', ['attribute' => __('article.intro_short')]),
            'status.required' => __('validation.required', ['attribute' => __('article.status')]),
        ];
    }
}
