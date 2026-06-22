<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualitzarEstudiant;
use App\Http\Requests\CrearEstudiant;
use App\Http\Resources\EstudiantResource;
use App\Models\Estudiant;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Estudiants iLERNA',
    description: 'API REST per a la gestió d\'estudiants de iLERNA. CRUD amb validacions, paginació i soft deletes.'
)]
#[OA\Server(url: '/api', description: 'Servidor local')]
class EstudiantController extends Controller
{
    use ApiResponse;

    /**
     * Llistar tots els estudiants (paginats).
     */
    #[OA\Get(
        path: '/estudiants',
        operationId: 'llistarEstudiants',
        tags: ['Estudiants'],
        summary: 'Llistar tots els estudiants',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Llista paginada d\'estudiants',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Estudiant')),
                        new OA\Property(property: 'count', type: 'integer', example: 20),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
        ]
    )]
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
