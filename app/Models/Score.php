<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'score_total',
        'niveau_risque',
        'detail_scores',
        'calculated_by',
        'calculated_at',
    ];

    protected $casts = [
        'score_total'   => 'decimal:2',
        'detail_scores' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function calculatedBy()
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }

    public function historique()
    {
        return $this->hasMany(HistoriqueScore::class);
    }
}