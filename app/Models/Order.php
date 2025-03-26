<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'service_id',
        'mechanic_id',
        'data',
        'statusas',
        'komentarai',
        'kaina',
        'status',
        'total_price',
        'description'
    ];

    protected $casts = [
        'kaina' => 'float',
        'total_price' => 'float',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}