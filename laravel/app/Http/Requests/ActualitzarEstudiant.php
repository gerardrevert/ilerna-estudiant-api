<?php

namespace App\Http\Requests;

use App\Rules\DniNie;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('estudiants', 'email')->ignore($id)],
            'telefon' => ['sometimes', 'required', 'string', 'regex:/^[6789]\d{8}$/'],
            'adreca' => ['nullable', 'string', 'max:255'],
            'numero_document_identitat' => ['nullable', 'string', 'max:20', Rule::unique('estudiants', 'numero_document_identitat')->ignore($id), new DniNie],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'telefon.regex' => 'El telèfon ha de ser un número espanyol de 9 dígits.',
        ];
    }
}
