<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'montant',
        'mode_paiement',
        'statut',
        'reference_transaction',
        'date_paiement',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}