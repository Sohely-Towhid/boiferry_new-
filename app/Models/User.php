<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id',
        'name',
        'email',
        'password',
        'mobile',
        'status',
        'role',
        'balance',
        'dob',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the Role
     *
     * @param  string  $value
     * @return string
     */
    public function getRoleAttribute($value)
    {
        return unserialize($value)[0];
    }

    /**
     * Get the invoices for the record.
     */
    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'user_id', 'id');
    }

    /**
     * Get the vendor record associated with the record.
     */
    public function vendor()
    {
        return $this->hasOne('App\Models\Vendor', 'id', 'vendor_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['email' => $this->email, 'name' => $this->name, 'mobile' => $this->mobile];
    }
}
