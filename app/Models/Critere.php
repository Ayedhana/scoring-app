<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'ponderation',
        'valeur_min',
        'valeur_max',
        'is_active',
    ];

    protected $casts = [
        'ponderation' => 'decimal:2',
        'valeur_min' => 'decimal:2',
        'valeur_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}