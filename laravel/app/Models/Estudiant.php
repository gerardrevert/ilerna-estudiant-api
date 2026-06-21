<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiant extends Model
{
    /** @use HasFactory<\Database\Factories\EstudiantFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telefon',
        'adreca',
        'numero_document_identitat',
    ];
}
