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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
                'message' => 'Estudiant no trobat.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
