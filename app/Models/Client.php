<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'vardas',
        'pavarde',
        'tel_numeris',
        'el_pastas',
        'registracijos_data'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}