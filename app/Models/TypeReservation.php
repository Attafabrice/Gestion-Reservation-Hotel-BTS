<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resrvation;

class TypeReservation extends Model
{
    use HasFactory; 

    protected $fillable = [
        'libelle',
        // 'prix',
        'description',
    ];
    
    protected $cats = [
    'prix' => 'decimal:2',
    ];

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
}
