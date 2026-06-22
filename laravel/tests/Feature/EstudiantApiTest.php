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

    #[Test]
    public function crear_un_estudiant_sense_camps_opcionals(): void
    {
        $data = [
            'nom' => 'Ana Garcia',
            'email' => 'ana@ilerna.com',
            'telefon' => '987654321',
        ];

        $response = $this->postJson('/api/estudiants', $data);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('estudiants', [
            'email' => 'ana@ilerna.com',
            'adreca' => null,
            'numero_document_identitat' => null,
        ]);
    }

    #[Test]
    public function no_pot_crear_estudiant_sense_nom(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'email' => 'test@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nom']);
    }

    #[Test]
    public function no_pot_crear_estudiant_amb_nom_molt_curt(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Ana', // 3 caracters, minim 5
            'email' => 'test@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nom']);
    }

    #[Test]
    public function no_pot_crear_estudiant_amb_nom_molt_llarg(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => str_repeat('a', 61), // 61 caracters, maxim 60
            'email' => 'test@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nom']);
    }

    #[Test]
    public function no_pot_crear_estudiant_sense_email(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Gerard Revert',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function no_pot_crear_estudiant_amb_email_invalit(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Gerard Revert',
            'email' => 'email-no-valit',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function no_pot_crear_estudiant_amb_email_duplicat(): void
    {
        Estudiant::factory()->create(['email' => 'duplicat@ilerna.com']);

        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Un altre nom',
            'email' => 'duplicat@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function no_pot_crear_estudiant_sense_telefon(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Gerard Revert',
            'email' => 'test@ilerna.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telefon']);
    }

    #[Test]
    public function crear_estudiant_amb_nom_exactament_5_caracters(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Gerar', // 5 caracters exactes
            'email' => 'gerar@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function crear_estudiant_amb_nom_exactament_59_caracters(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => str_repeat('a', 59), // 59 caracters exactes
            'email' => 'test59@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }    

}
