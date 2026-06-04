<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TypeReservation;
use App\Models\Chambre;
use App\Models\User;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_reservation', 
        'user_id',
        'chambre_id',
        'type_reservation_id',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'nombre_jours',
        'nombres_heures',
        'prix_total',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'prix_total' => 'decimal:2',
    ];

    // Génération automatique du code à la création
    protected static function booted(): void{
        static::creating(function (Reservation $reservation) {
            $reservation->code_reservation = self::genererCode();
        });
    }

     // Format : RES-2025-A3F7K2 (unique garanti)
    private static function genererCode(): string{
        do {
            $code = 'RES-' . now()->year . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('code_reservation', $code)->exists());

        return $code;
    }

    // Relation avec User
    public function user(){
        return $this->belongsTo(User::class);
    }

    // Relation avec Chambre
    public function chambre(){
        return $this->belongsTo(Chambre::class);
    }

    //  Relation avec TypeReservation
    public function typeReservation(){

        return $this->belongsTo(TypeReservation::class);
    }
    //Relation paiement
    public function paiements(){

    return $this->hasMany(Paiement::class);
    }


}