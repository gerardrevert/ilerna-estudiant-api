<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstudiantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'telefon' => $this->telefon,
            'adreca' => $this->adreca,
            'numero_document_identitat' => $this->numero_document_identitat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
