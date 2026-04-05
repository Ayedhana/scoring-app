<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $table = 'transactions';
    public $timestamps = false;

    public function compte()
    {
        return $this->belongsTo(Compte::class, 'compte_id', 'id');
    }
}
