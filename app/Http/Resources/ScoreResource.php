<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScoreResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'score_total' => $this->score_total,
            'niveau_risque' => $this->niveau_risque,
            'detail_scores' => $this->detail_scores,
            'calculated_at' => $this->calculated_at?->toDateTimeString(),
            'calculated_by' => $this->whenLoaded('calculatedBy', function () {
                return $this->calculatedBy ? [
                    'id' => $this->calculatedBy->id,
                    'name' => $this->calculatedBy->name,
                    'email' => $this->calculatedBy->email,
                ] : null;
            }),
            'client' => $this->whenLoaded('client', function () {
                return new ClientResource($this->client);
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
