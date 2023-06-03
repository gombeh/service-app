<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'customer_name', 'origin_coordinate', 'destination_coordinate'];

    protected $casts = [
        'origin_coordinate' => Point::class,
        'destination_coordinate' => Point::class
    ];
}
