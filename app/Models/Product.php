<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'vendor_id',
        'category_id',
        'brand_id',
        'name',
        'images',
        'short_description',
        'description',
        'seo',
        'sku',
        'size',
        'color',
        'rate',
        'sale',
        'stock',
        'actual_stock',
        'sold',
        'point',
        'shelf',
        'type',
        'rating_review',
        'in_home',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'images'        => 'array',
        'rating_review' => 'object',
        'seo'           => 'object',
    ];
}
