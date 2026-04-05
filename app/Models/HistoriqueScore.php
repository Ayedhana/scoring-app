<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueScore extends Model
{
    use HasFactory;

    protected $table = 'historique_scores';

    protected $fillable = [
        'client_id',
        'score_id',
        'score_total',
        'niveau_risque',
        'detail_scores',
        'created_by',
    ];

    protected $casts = [
        'score_total'   => 'decimal:2',
        'detail_scores' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function score()
    {
        return $this->belongsTo(Score::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}