<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:posts,title',
            'slug' => 'nullable|string|unique:posts,slug',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:5000',
            'featured' => 'required|boolean',
            'tag_id' => 'required|array',
            'tag_id.*' => 'exists:tags,id',

        ];
    }
}
