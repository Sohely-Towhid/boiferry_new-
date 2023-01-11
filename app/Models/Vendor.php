<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'mobile',
        'email',
        'address',
        'logos',
        'banners',
        'category',
        'files',
        'details',
        'followers',
        'rating',
        'fee',
        'book',
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
        'logos'    => 'array',
        'banners'  => 'array',
        'files'    => 'array',
        'category' => 'array',
        'details'  => 'array',
    ];

    /**
     * Get the user record associated with the record.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Get the bank details.
     *
     * @param  string  $value
     * @return string
     */
    public function getBankAttribute()
    {
        $bank             = @$this->details['bank'];
        $bank['verified'] = ($bank['verified']) ? 'Verified Account' : 'Non Verified Account';
        return @implode("\n", $bank);
    }

}
