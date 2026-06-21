<?php

namespace Tests\Feature;

use App\Models\Estudiant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EstudiantApiTest extends TestCase
{

    use RefreshDatabase;
    // ==========================================
    // GET /api/estudiants - Listar estudiants
    // ==========================================

    #[Test]
    public function llistar_tots_els_estudiants(): void
    {
        Estudiant::factory()->count(3)->create();

        $response = $this->getJson('/api/estudiants');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'nom', 'email', 'telefon', 'adreca', 'numero_document_identitat', 'created_at', 'updated_at']
                ],
                'count',
            ])
            ->assertJson(['success' => true, 'count' => 3]);
    }


    #[Test]
    public function llistar_usuaris_quan_esta_vuit(): void
    {
        $response = $this->getJson('/api/estudiants');

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'count' => 0, 'data' => []]);
    }

    // ==========================================
    // POST /api/estudiants - Crear estudiant
    // ==========================================

    #[Test]
    public function crear_estudiant_amb_dades_valides(): void
    {
        $data = [
            'nom' => 'Gerard Revert',
            'email' => 'gerardrevert@ilerna.com',
            'telefon' => '690203376',
            'adreca' => 'Calle Mayor 123, Balaguer',
            'numero_document_identitat' => '12345678A',
        ];

        $response = $this->postJson('/api/estudiants', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'nom', 'email', 'telefon', 'adreca', 'numero_document_identitat', 'created_at', 'updated_at'],
            ])
            ->assertJson(['success' => true, 'message' => 'Estudiant creat correctament']);

        $this->assertDatabaseHas('estudiants', ['email' => 'gerardrevert@ilerna.com']);
    }    
}
