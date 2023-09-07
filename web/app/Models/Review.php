<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = [
        'name',
        'shop_id',
        'product_id',
        'rating',
        'comment'
    ];

    protected $dates = [
        'updated_at',
        'created_at'
    ];
}
