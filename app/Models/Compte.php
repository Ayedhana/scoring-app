<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $table = 'comptes';
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'compte_id', 'id');
    }
}
