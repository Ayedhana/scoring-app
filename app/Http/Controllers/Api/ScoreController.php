<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateScoreRequest;
use App\Http\Resources\ScoreResource;
use App\Models\Client;
use App\Models\Score;
use App\Services\ScoringService;

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
        $client = Client::findOrFail($request->client_id);

        $score = $this->scoringService->calculate($client, $request->validated(), auth()->id());

        return new ScoreResource($score);
    }
}
