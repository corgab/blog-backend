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
            'content'=> 'nullable|string',
            'image' => ' nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'featured' => 'required|boolean',
            'status' => 'required|in:draft,published',
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

            'image.image' => 'Il file caricato deve essere un\'immagine.',
            'image.mimes' => 'Il file deve essere un\'immagine nei formati: jpeg, png, webp.',
            'image.max' => 'L\'immagine non può superare i :max kilobyte.',

            'featured.required' => 'Il campo "In Evidenza" è obbligatorio.',
            'featured.boolean' => 'Il campo "In Evidenza" deve essere vero o falso.',

            'status.required' => 'Il campo "Stato" è obbligatorio.',
            'status.in' => 'Lo stato deve essere "bozza" o "pubblicato".',

            'tag_id.required' => 'È necessario selezionare almeno un tag.',
            'tag_id.array' => 'I tag devono essere in un formato valido.',
            'tag_id.*.exists' => 'Il tag selezionato non è valido.',

        ];
    }

}
