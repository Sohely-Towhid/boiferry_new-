<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceMeta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'vendor_id',
        'book_id',
        'quantity',
        'rate',
        'discount',
        'other_data',
        'product',
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
        'other_data' => 'object',
        'product'    => 'object',
    ];

    /**
     * Get the vendor record associated with the record.
     */
    public function vendor()
    {
        return $this->hasOne('App\Models\Vendor', 'id', 'vendor_id');
    }

    /**
     * Get the book record associated with the record.
     */
    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }

    /**
     * Get the invoice record associated with the record.
     */
    public function invoice()
    {
        return $this->hasOne('App\Models\Invoice', 'id', 'invoice_id');
    }

    /**
     * Get the user record associated with the record.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
