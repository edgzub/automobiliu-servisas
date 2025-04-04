<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'marke',
        'modelis',
        'metai',
        'valstybinis_numeris',
        'vin_kodas',
        'brand',
        'model',
        'year',
        'plate_number',
        'vin',
        'description'
    ];
    
    protected $casts = [
        'metai' => 'integer',
        'year' => 'integer',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}