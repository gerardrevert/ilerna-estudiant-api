<?php

namespace Tests\Feature;

use App\Models\Estudiant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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
                    '*' => ['id', 'nom', 'email', 'telefon', 'adreca', 'numero_document_identitat', 'created_at', 'updated_at'],
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
            'numero_document_identitat' => '12345678Z',
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

    #[Test]
    public function no_pot_crear_estudiant_amb_telefon_invalid(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Gerard Revert',
            'email' => 'test@ilerna.com',
            'telefon' => '123456789', // No comença per 6, 7, 8 o 9
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telefon']);
    }

    #[Test]
    public function no_pot_crear_estudiant_amb_dni_invalid(): void
    {
        $response = $this->postJson('/api/estudiants', [
            'nom' => 'Gerard Revert',
            'email' => 'test@ilerna.com',
            'telefon' => '690203376',
            'numero_document_identitat' => '12345678A', // Lletra incorrecta
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['numero_document_identitat']);
    }

    // ==========================================
    // GET /api/estudiants/{id} - Mostrar estudiant per id
    // ==========================================

    #[Test]
    public function mostrar_un_estudiant_existent(): void
    {
        $estudiant = Estudiant::factory()->create([
            'nom' => 'Gerard Revert',
            'email' => 'gerard@ilerna.com',
        ]);

        $response = $this->getJson("/api/estudiants/{$estudiant->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $estudiant->id,
                    'nom' => 'Gerard Revert',
                    'email' => 'gerard@ilerna.com',
                ],
            ]);
    }

    #[Test]
    public function retorna_404_si_el_estudiant_no_existeix(): void
    {
        $response = $this->getJson('/api/estudiants/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Estudiant no trobat',
            ]);
    }

    // ==========================================
    // PUT /api/estudiants/{id} - Actualizar estudiant especific
    // ==========================================

    #[Test]
    public function actualitzar_un_estudiant_completament(): void
    {
        $estudiant = Estudiant::factory()->create([
            'nom' => 'Nom Original',
            'email' => 'original@ilerna.com',
            'telefon' => '111111111',
        ]);

        $response = $this->putJson("/api/estudiants/{$estudiant->id}", [
            'nom' => 'Nom Actualitzat',
            'email' => 'actualitzacio@ilerna.com',
            'telefon' => '999999999',
            'adreca' => 'Nova direccio 456',
            'numero_document_identitat' => '87654321X',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Estudiant actualitzat correctament',
                'data' => [
                    'nom' => 'Nom Actualitzat',
                    'email' => 'actualitzacio@ilerna.com',
                    'telefon' => '999999999',
                ],
            ]);

        $this->assertDatabaseHas('estudiants', ['email' => 'actualitzacio@ilerna.com']);
    }

    #[Test]
    public function pot_actualitzar_parcialment_un_estudiant(): void
    {
        $estudiant = Estudiant::factory()->create([
            'nom' => 'Nom Original',
            'email' => 'original@ilerna.com',
            'telefon' => '111111111',
        ]);

        $response = $this->putJson("/api/estudiants/{$estudiant->id}", [
            'nom' => 'Nomes nom cambiat',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'nom' => 'Nomes nom cambiat',
                    'email' => 'original@ilerna.com', // No cambia
                ],
            ]);
    }

    #[Test]
    public function no_pot_actualitzar_amb_email_duplicat_de_un_altre_estudiant(): void
    {
        $estudiant1 = Estudiant::factory()->create(['email' => 'estudiant1@ilerna.com']);
        $estudiant2 = Estudiant::factory()->create(['email' => 'estudiant2@ilerna.com']);

        $response = $this->putJson("/api/estudiants/{$estudiant2->id}", [
            'email' => 'estudiant1@ilerna.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function retorna_404_al_actualitzar_estudiant_inexistent(): void
    {
        $response = $this->putJson('/api/estudiants/99999', [
            'nom' => 'Nombre inexistent',
            'email' => 'nuevo@ilerna.com',
            'telefon' => '690203376',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Estudiant no trobat',
            ]);
    }

    // ==========================================
    // DELETE /api/estudiants/{id} - Eliminar estudiants
    // ==========================================

    #[Test]
    public function eliminar_un_estudiante(): void
    {
        $estudiant = Estudiant::factory()->create();

        $response = $this->deleteJson("/api/estudiants/{$estudiant->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Estudiant eliminat correctament',
            ]);

        $this->assertSoftDeleted('estudiants', ['id' => $estudiant->id]);
    }

    #[Test]
    public function retorna_404_al_eliminar_estudiant_inexistent(): void
    {
        $response = $this->deleteJson('/api/estudiants/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Estudiant no trobat',
            ]);
    }

    // ==========================================
    // Health Check per poder fer pings
    // ==========================================

    #[Test]
    public function health_check_basic(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'API iLERNA estudiants funciona correctament',
            ]);
    }
}
