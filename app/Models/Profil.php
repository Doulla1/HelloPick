<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = ['nom', 'prenom', 'image', 'statut'];

    // Define default values for field statut
    protected $attributes = [
        'statut' => 'en attente',
    ];
}
