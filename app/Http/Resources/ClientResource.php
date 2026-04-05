<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'numero_client' => $this->numero_client,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'cin' => $this->cin,
            'date_naissance' => $this->date_naissance?->toDateString(),
            'telephone' => $this->telephone,
            'email' => $this->email,
            'adresse' => $this->adresse,
            'type_client' => $this->type_client,
            'solde_moyen' => $this->solde_moyen,
            'nombre_incidents' => $this->nombre_incidents,
            'montant_credits' => $this->montant_credits,
            'anciennete_mois' => $this->anciennete_mois,
            'is_active' => $this->is_active,
            'dernier_score' => $this->whenLoaded('dernierScore', function () {
                return $this->dernierScore ? [
                    'score_total' => $this->dernierScore->score_total,
                    'niveau_risque' => $this->dernierScore->niveau_risque,
                    'calculated_at' => optional($this->dernierScore->calculated_at)->toDateTimeString(),
                ] : null;
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
