<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Score;
use App\Models\HistoriqueScore;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScoringService
{
    /**
     * Calcule et sauvegarde le score d'un client.
     */
    public function calculate(Client $client, array $payload, ?int $userId = null): Score
    {
        $userId = $userId ?? null;

        return DB::transaction(function () use ($client, $payload, $userId) {
            $accountAgePoints = $this->scoreAccountAge($client->anciennete_mois ?? 0);
            $paymentIncidentPoints = $this->scorePaymentIncidents($client->nombre_incidents ?? 0);
            $creditAmountPoints = $this->scoreCreditAmount($client->montant_credits ?? 0);
            $avgBalancePoints = $this->scoreAverageBalance($client->solde_moyen ?? 0);

            $total = round($accountAgePoints + $paymentIncidentPoints + $creditAmountPoints + $avgBalancePoints, 2);

            $riskLevel = $this->determineRiskLevel($total);

            $score = Score::create([
                'client_id' => $client->id,
                'score_total' => $total,
                'niveau_risque' => $riskLevel,
                'detail_scores' => [
                    'account_age' => $accountAgePoints,
                    'payment_incidents' => $paymentIncidentPoints,
                    'credit_amount' => $creditAmountPoints,
                    'avg_balance' => $avgBalancePoints,
                ],
                'calculated_by' => $userId,
                'calculated_at' => Carbon::now(),
            ]);

            HistoriqueScore::create([
                'client_id' => $client->id,
                'score_id' => $score->id,
                'score_total' => $score->score_total,
                'niveau_risque' => $score->niveau_risque,
                'detail_scores' => $score->detail_scores,
                'created_by' => $userId,
            ]);

            return $score;
        });
    }

    protected function scoreAccountAge(int $months): float
    {
        if ($months >= 60) {
            return 300;
        }

        if ($months >= 36) {
            return 220;
        }

        if ($months >= 12) {
            return 150;
        }

        return 70;
    }

    protected function scorePaymentIncidents(int $count): float
    {
        if ($count === 0) {
            return 300;
        }

        if ($count <= 2) {
            return 180;
        }

        if ($count <= 5) {
            return 90;
        }

        return 30;
    }

    protected function scoreCreditAmount(float $amount): float
    {
        if ($amount <= 10000) {
            return 300;
        }

        if ($amount <= 50000) {
            return 200;
        }

        if ($amount <= 100000) {
            return 120;
        }

        return 50;
    }

    protected function scoreAverageBalance(float $avgBalance): float
    {
        if ($avgBalance >= 50000) {
            return 300;
        }

        if ($avgBalance >= 20000) {
            return 210;
        }

        if ($avgBalance >= 5000) {
            return 130;
        }

        return 60;
    }

    protected function determineRiskLevel(float $total): string
    {
        if ($total >= 900) {
            return 'faible';
        }

        if ($total >= 600) {
            return 'moyen';
        }

        return 'eleve';
    }
}
