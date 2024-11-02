<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'brand',
        'description',
        'ingredients',
        'nutritional_score',
        'quantity',
        'imported_t',
        'status',
    ];

    protected $casts = [
        'ingredients' => 'json',
        'imported_t' => 'datetime',
    ];
}
