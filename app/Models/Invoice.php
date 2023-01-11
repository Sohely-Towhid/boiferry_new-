<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'processed_by',
        'lock_by',
        'billing_id',
        'shipping_id',
        'session',
        'note',
        'shipping',
        'total',
        'discount',
        'coupon_discount',
        'partial_payment',
        'coupon',
        'payment',
        'print',
        'shipment_date',
        'delivery_date',
        'last_mail',
        'system_note',
        'tracking',
        'referral',
        'gift_wrap',
        'stock_update',
        'status',
        'pre_order',
        'packed',
        'lock_at',
        'last_call',
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
        'system_note' => 'object',
    ];

    /**
     * Get the user record associated with the record.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Get the user record associated with the record.
     */
    public function lock()
    {
        return $this->hasOne('App\Models\User', 'id', 'lock_by');
    }

    /**
     * Get the metas for the record.
     */
    public function metas()
    {
        return $this->hasMany('App\Models\InvoiceMeta', 'invoice_id', 'id');
    }

    /**
     * Get the address record associated with the record.
     */
    public function billing_address()
    {
        return $this->hasOne('App\Models\Address', 'id', 'billing_id');
    }

    /**
     * Get the address record associated with the record.
     */
    public function shipping_address()
    {
        return $this->hasOne('App\Models\Address', 'id', 'shipping_id');
    }
}
