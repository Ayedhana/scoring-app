<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RapportController extends Controller
{
   public function stats()
{
    $total = \App\Models\Client::count();

    $scores = \App\Models\Score::all();

    $moyenne = $scores->avg('score_total') ?? 0;

    $acceptes = $scores->filter(fn($s) => strtolower($s->niveau_risque) === 'faible')->count();
    $refuses = $scores->filter(fn($s) => strtolower($s->niveau_risque) === 'eleve')->count();

    return response()->json([
        'total_clients' => $total,
        'score_moyen' => round($moyenne, 2),
        'acceptes' => $acceptes,
        'refuses' => $refuses
    ]);
}
}
