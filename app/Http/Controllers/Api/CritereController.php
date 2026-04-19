<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Critere;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CritereController extends Controller
{
    /**
     * Afficher tous les critères
     */
    public function index(): JsonResponse
    {
        $criteres = Critere::where('is_active', true)->get();
        return response()->json($criteres);
    }

    /**
     * Afficher un critère spécifique
     */
    public function show(Critere $critere): JsonResponse
    {
        return response()->json($critere);
    }

    /**
     * Créer un nouveau critère
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'code'        => 'required|string|unique:criteres,code',
            'description' => 'nullable|string',
            'ponderation' => 'required|numeric|min:0|max:100',
            'valeur_min'  => 'nullable|numeric',
            'valeur_max'  => 'nullable|numeric',
            'is_active'   => 'boolean',
        ]);

        $critere = Critere::create($validated);

        return response()->json([
            'message' => 'Critère créé avec succès',
            'critere' => $critere
        ], 201);
    }

    /**
     * Modifier un critère existant
     */
    public function update(Request $request, Critere $critere): JsonResponse
    {
        $validated = $request->validate([
            'nom'         => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'ponderation' => 'sometimes|numeric|min:0|max:100',
            'valeur_min'  => 'nullable|numeric',
            'valeur_max'  => 'nullable|numeric',
            'is_active'   => 'boolean',
        ]);

        $critere->update($validated);

        return response()->json([
            'message' => 'Critère mis à jour avec succès',
            'critere' => $critere
        ]);
    }

    /**
     * Mettre à jour toutes les pondérations en une seule fois
     */
    public function updateAll(Request $request): JsonResponse
{
    $request->validate([
        'criteres'               => 'required|array',
        'criteres.*.id'          => 'required|exists:criteres,id',
        'criteres.*.ponderation' => 'required|numeric|min:0|max:100',
    ]);

    // Convertir et calculer le total
    $total = collect($request->criteres)
        ->sum(fn($item) => floatval($item['ponderation']));

    // Tolérance de 0.01 pour les arrondis
    if (abs($total - 100) > 0.01) {
        return response()->json([
            'message' => 'La somme des pondérations doit être égale à 100%',
            'total'   => $total
        ], 422);
    }

    foreach ($request->criteres as $item) {
        Critere::where('id', $item['id'])
            ->update(['ponderation' => floatval($item['ponderation'])]);
    }

    return response()->json([
        'message' => 'Pondérations mises à jour avec succès'
    ]);
}
    /**
     * Supprimer un critère
     */
    public function destroy(Critere $critere): JsonResponse
    {
        $critere->delete();

        return response()->json([
            'message' => 'Critère supprimé avec succès'
        ]);
    }
}