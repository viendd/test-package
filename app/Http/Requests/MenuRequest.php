<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        return [
             'name' => 'required|min:5|max:255',
             'language_id' => 'required',
             'uri' => 'required',
             'order_no' => 'required'
        ];
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
                'name.required' => __('validation.required', ['attribute' => __('author.name')]),
                'name.min' => __('validation.min', ['attribute' => __('author.name')]),
                'name.max' => __('validation.min', ['attribute' => __('author.name')]),
                'uri.required' => __('validation.required', ['attribute' => 'Uri']),
                'order_no.required' => __('validation.required', ['attribute' => __('menu.order_no')]),
            ];
    }
}
