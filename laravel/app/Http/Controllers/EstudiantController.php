<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualitzarEstudiant;
use App\Http\Requests\CrearEstudiant;
use App\Http\Resources\EstudiantResource;
use App\Models\Estudiant;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;


class EstudiantController extends Controller
{
    use ApiResponse;

    /**
     * Llistar tots els estudiants (paginats).
     * GET /api/estudiants
     */
    public function index(): JsonResponse
    {
        $estudiants = Estudiant::paginate(20);

        return $this->successResponse(
            EstudiantResource::collection($estudiants->items()),
            '',
            200,
            [
            'count' => $estudiants->count(),
                'meta' => [
                    'current_page' => $estudiants->currentPage(),
                    'last_page' => $estudiants->lastPage(),
                    'per_page' => $estudiants->perPage(),
                    'total' => $estudiants->total(),
                ],
            ]
        );
    }

    /**
     * Crear un nou estudiant
     * POST /api/estudiants
     */
    public function store(CrearEstudiant $request): JsonResponse
    {
        $estudiant = Estudiant::create($request->validated());

        return $this->successResponse(
            new EstudiantResource($estudiant),
            'Estudiant creat correctament',
            201
        );
    }

    /**
     * Mostrar un estudiant especific
     * GET /api/estudiants/{estudiantID}
     */
    public function show(Estudiant $estudiant): JsonResponse
    {
        return $this->successResponse(new EstudiantResource($estudiant));
    }

    /**
     * Actualitzar un estudiant existent per ID
     * PUT /api/estudiants/{estudiantID}
     */
    public function update(ActualitzarEstudiant $request, Estudiant $estudiant): JsonResponse
    {
            $estudiant->update($request->validated());

        return $this->successResponse(
            new EstudiantResource($estudiant->fresh()),
            'Estudiant actualitzat correctament'
        );
    }

    /**
     * Eliminar un estudiant (soft delete).
     * DELETE /api/estudiants/{estudiant}
     */
    public function destroy(Estudiant $estudiant): JsonResponse
    {
            $estudiant->delete();

        return $this->successResponse(null, 'Estudiant eliminat correctament');
    }
}
