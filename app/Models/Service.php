<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'pavadinimas',
        'aprasymas',
        'kaina',
        'trukme_valandomis',
        'kategorija'
    ];

    // Pridedame duomenų konvertavimą
    protected $casts = [
        'kaina' => 'float',
        'trukme_valandomis' => 'float',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}