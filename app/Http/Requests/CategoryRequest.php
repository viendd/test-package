<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules()
    {
        $id = \request()->input('id') ?? '';
        return [
            'language_id' => 'required',
            'order' => 'required|integer',
            'name' => 'required|unique:categories,name,'.$id,
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
            'name.required' => __('validation.required', ['attribute' => __('category.category_name')]),
            'order.required' => __('validation.required', ['attribute' => __('category.order')]),
            'order.integer' => __('validation.integer', ['attribute' => __('category.order')]),
            'name.unique' => __('validation.unique', ['attribute' => __('category.category_name')]),
        ];
    }
}
