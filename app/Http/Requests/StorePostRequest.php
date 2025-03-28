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
            'description' => 'nullable|string|max:500',
            'content'=> 'nullable|string|max:65000',
            'image' => ' nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'featured' => 'required|boolean',
            'tag_id' => 'required|array',
            'tag_id.*' => 'exists:tags,id',
            'meta_description' => 'nullable|string|max:255',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.string' => 'Il titolo deve essere una stringa.',
            'title.max' => 'Il titolo non può superare i :max caratteri.',
    
            'slug.string' => 'Lo slug deve essere una stringa.',
            'slug.unique' => 'Lo slug è già stato utilizzato.',
    
            'image.image' => 'Il file caricato deve essere un\'immagine.',
            'image.mimes' => 'Il file deve essere un\'immagine nei formati: jpeg, png, webp.',
            'image.max' => 'L\'immagine non può superare i :max kilobyte.',
    
            'featured.required' => 'Il campo "In Evidenza" è obbligatorio.',
            'featured.boolean' => 'Il campo "In Evidenza" deve essere vero o falso.',
    
            'tag_id.required' => 'È necessario selezionare almeno un tag.',
            'tag_id.array' => 'I tag devono essere in un formato valido.',
            'tag_id.*.exists' => 'Il tag selezionato non è valido.',
    
        ];
    }
    
}
