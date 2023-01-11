<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'book_id',
        'user_id',
        'vendor_id',
        'category_id',
        'coupon_type',
        'author_id',
        'publisher_id',
        'code',
        'amount',
        'min_shopping',
        'type',
        'min',
        'max_use',
        'used',
        'start',
        'expire',
        'status',
        'referral',
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
        'product_id'   => 'array',
        'book_id'      => 'array',
        'author_id'    => 'array',
        'vendor_id'    => 'array',
        'publisher_id' => 'array',
    ];
}
