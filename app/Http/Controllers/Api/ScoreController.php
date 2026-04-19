<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateScoreRequest;
use App\Http\Resources\ScoreResource;
use App\Models\Client;
use App\Models\Score;
use App\Services\ScoringService;
use App\Services\T24MockService;

class ScoreController extends Controller
{
    protected ScoringService $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    public function index()
    {
        $scores = Score::with(['client', 'calculatedBy'])->paginate(20);

        return ScoreResource::collection($scores);
    }

    public function show(Score $score)
    {
        return new ScoreResource($score->load(['client', 'calculatedBy']));
    }

    public function history(Client $client)
    {
        $history = $client->scores()->with('calculatedBy')->orderBy('calculated_at', 'desc')->paginate(20);

        return ScoreResource::collection($history);
    }

   public function calculate(CalculateScoreRequest $request)
{
    try {
        $t24 = new T24MockService();
        $clientData = $t24->fetchClient($request->client_id);

        if (!$clientData) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        // Créer ou mettre à jour le client dans Oracle
        $client = Client::updateOrCreate(
            ['numero_client' => $request->client_id],
            [
                'nom'              => $clientData['nom'] ?? 'Inconnu',
                'prenom'           => $clientData['prenom'] ?? '',
                'email'            => $clientData['email'] ?? '',
                'solde_moyen'      => floatval($clientData['solde_moyen'] ?? 0),
                'anciennete_mois'  => intval($clientData['anciennete_mois'] ?? 0),
                'nombre_incidents' => intval($clientData['nombre_incidents'] ?? 0),
                'montant_credits'  => floatval($clientData['montant_credits'] ?? 0),
                'is_active'        => true,
            ]
        );

        $score = $this->scoringService->calculate(
            $client,
            [],
            auth()->id()
        );

        return response()->json([
            'message' => 'Score calculé avec succès',
            'score'   => [
                'score_total'  => $score->score_total,
                'niveau_risque'=> $score->niveau_risque,
                'detail_scores'=> $score->detail_scores,
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Score calculation error: ' . $e->getMessage());
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
}
}
