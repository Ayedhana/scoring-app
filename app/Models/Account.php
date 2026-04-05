<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'account_number',
        'branch',
        'account_type',
        'currency',
        'open_date',
        'balance',
        'status',
    ];

    protected $casts = [
        'open_date' => 'date',
        'balance' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
