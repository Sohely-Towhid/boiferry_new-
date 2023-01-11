<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'invoice_id',
        'cod',
        'coupon_discount',
        'total',
        'print',
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
    protected $casts = [];

    /**
     * Get the invoice record associated with the record.
     */
    public function invoice()
    {
        return $this->hasOne('App\Models\Invoice', 'id', 'invoice_id');
    }

    /**
     * Get the metas for the record.
     */
    public function metas()
    {
        return $this->hasMany('App\Models\InvoiceMeta', 'invoice_id', 'invoice_id');
    }
}
