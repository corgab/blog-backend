<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' =>'required|string|max:255',
            'slug' =>'nullable|string',
            'image' => ' nullable|image|mimes:jpeg,png,webp|max:2048', // Required
            'featured' => 'required|boolean',
            'status' => 'required|in:draft,published',
            'tag_id' => 'required|array',
            'tag_id.*' => 'exists:tags,id',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.content' => 'required|string',
        ];
    }
}
