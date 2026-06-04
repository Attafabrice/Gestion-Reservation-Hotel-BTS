<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tarif;

class TypeChambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'description',
        'equipements',
    ];

    // Cast pour récupérer le json en tableau automatiquement
    protected $casts = [
        'equipements' => 'array',
    ];
    
    public function tarifs(){
    return $this->hasMany(Tarif::class);
   }
}