<?php

namespace App\Http\Controllers;

use App\Models\Estudiant;
use App\Http\Requests\CrearEstudiant;
use App\Http\Requests\ActualitzarEstudiant;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;


class EstudiantController extends Controller
{
    /**
     * Llistar tots els estudiants
     * GET /api/estudiants
     */
    public function index(): JsonResponse
    {
        $estudiants = Estudiant::all();

        return response()->json([
            'success' => true,
            'data' => $estudiants,
            'count' => $estudiants->count(),
        ]);
    }

    /**
     * Crear un nou estudiant
     * POST /api/estudiants
     */
    public function store(CrearEstudiant $request): JsonResponse
    {
        $estudiant = Estudiant::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Estudiant creat correctament',
            'data' => $estudiant,
        ], 201 );
    }

    /**
     * Mostrar un estudiant especific
     * GET /api/estudiants/{estudiantID}
     */
    public function show(int $estudiant): JsonResponse
    {
        try {
            $estudiant = Estudiant::findOrFail($estudiant);

            return response()->json([
                'success' => true,
                'data' => $estudiant,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiant no trobat',
            ], 404);
        }
    }

    /**
     * Actualitzar un estudiant existent per ID
     * PUT /api/estudiants/{estudiantID}
     */
    public function update(ActualitzarEstudiant $request, int $estudiantID): JsonResponse
    {
        try {
            $estudiant = Estudiant::findOrFail($estudiantID);
            $estudiant->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Estudiant actualitzat correctament',
                'data' => $estudiant->fresh(),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiant no trobat',
            ], 404);
        }
    }

    /**
     * Eliminar un estudiant per ID
     * DELETE /api/estudiants/{estudiantID}
     */
    public function destroy(int $estudiantID): JsonResponse
    {
        try {
            $estudiant = Estudiant::findOrFail($estudiantID);
            $estudiant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Estudiant eliminat correctament'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiant no trobat.',
            ], 404);
        }
    }
}
