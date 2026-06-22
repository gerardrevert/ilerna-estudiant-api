<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ActualitzarEstudiant extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = $this->route('estudiant');

        return [
            'nom' => ['sometimes', 'required', 'string', 'min:5', 'max:59'],
            'email' => ['sometimes','required', 'email', 'max:255', Rule::unique('estudiants', 'email')->ignore($id)],
            'telefon' => ['sometimes', 'required' ,'string', 'max:20'],
            'adreca' => ['nullable', 'string', 'max:255'],
            'numero_document_identitat' => ['nullable', 'string', 'max:50', Rule::unique('estudiants', 'numero_document_identitat')->ignore($id)],
        ];
    }
}
