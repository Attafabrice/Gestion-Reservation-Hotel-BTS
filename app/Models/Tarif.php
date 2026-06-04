<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeReservation;
use App\Models\TypeChambre;
class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_chambre_id',
        'type_reservation_id',
        'prix',
    ];

    public function typeChambre():BelongsTo{
        
        return $this->belongsTo(TypeChambre::class);
    }

    public function typeReservation():BelongsTo{

        return $this->belongsTo(TypeReservation::class);
    }

}
