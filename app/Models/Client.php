<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Compte;
use App\Models\Credit;

class Client extends Model
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $table = 'clients';
    public $timestamps = false;

    protected $fillable = [
        'numero_client',
        'nom',
        'prenom',
        'cin',
        'date_naissance',
        'telephone',
        'email',
        'adresse',
        'type_client',
        'solde_moyen',
        'nombre_incidents',
        'montant_credits',
        'anciennete_mois',
        'is_active',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'solde_moyen'    => 'decimal:2',
        'montant_credits' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function comptes()
    {
        return $this->hasMany(Compte::class, 'client_id', 'id');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'client_id', 'id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function historiqueScores()
    {
        return $this->hasMany(HistoriqueScore::class);
    }

    public function dernierScore()
    {
        return $this->hasOne(Score::class)->latestOfMany();
    }
}