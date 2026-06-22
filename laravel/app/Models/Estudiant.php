<?php

namespace App\Models;

use Database\Factories\EstudiantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estudiant extends Model
{
    /** @use HasFactory<EstudiantFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'email',
        'telefon',
        'adreca',
        'numero_document_identitat',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
