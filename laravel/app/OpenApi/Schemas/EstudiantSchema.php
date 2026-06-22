<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Estudiant',
    title: 'Estudiant',
    description: 'Model d\'estudiant',
    required: ['id', 'nom', 'email', 'telefon']
)]
class EstudiantSchema
{
    #[OA\Property(description: 'Identificador únic de l\'estudiant', example: 1)]
    public int $id;

    #[OA\Property(description: 'Nom de l\'estudiant', example: 'Nom Exemple')]
    public string $nom;

    #[OA\Property(description: 'Correu electrònic', example: 'exemple@ilerna.com')]
    public string $email;

    #[OA\Property(description: 'Telèfon de contacte', example: '690203376')]
    public string $telefon;

    #[OA\Property(description: 'Adreça postal', nullable: true, example: 'Carrer Major 123')]
    public ?string $adreca;

    #[OA\Property(description: 'Número de document d\'identitat', nullable: true, example: '12345678Z')]
    public ?string $numero_document_identitat;

    #[OA\Property(description: 'Data de creació', example: '2026-06-21T23:36:39.000000Z')]
    public string $created_at;

    #[OA\Property(description: 'Data d\'actualització', example: '2026-06-21T23:36:39.000000Z')]
    public string $updated_at;
}
