<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CrearEstudiant extends FormRequest
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
    return [
        'nom' => ['required', 'string', 'min:5', 'max:59'],
        'email' => ['required', 'email', 'unique:estudiants,email'],
        'telefon' => ['required', 'string', 'max:20'],
        'adreca' => ['nullable', 'string', 'max:255'],
        'numero_document_identitat' => ['nullable', 'string', 'max:50', 'unique:estudiants,numero_document_identitat'],
    ];
    }
}
