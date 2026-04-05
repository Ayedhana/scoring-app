<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    /**
     * Return all comptes with client and transaction relationships.
     */
    public function getAllComptes(Request $request)
    {
        $comptes = Compte::with(['client', 'transactions'])
            ->paginate(20);

        return response()->json($comptes);
    }

    /**
     * Return a single compte by id with relationships.
     */
    public function getCompteDetails($id)
    {
        $compte = Compte::with(['client', 'transactions'])->find($id);

        if (! $compte) {
            return response()->json(['message' => 'Compte not found'], 404);
        }

        return response()->json($compte);
    }
}
