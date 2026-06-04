<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeChambre;

class Chambre extends Model
{
    use HasFactory;
    protected $fillable = [ 
      'numero',
    'etage',
    'description',
    'capacite',
    'surface',
    'image',
    'type_chambre_id',
    'statut',
    ];

    public function type(){
        
        return $this->belongsTo(TypeChambre::class, 'type_chambre_id');
    }
}
