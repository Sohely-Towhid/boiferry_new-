<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'price',
        'pg',
        'payment',
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
        'payment' => 'array',
    ];

    public $status_text = ['unpaid', 'paid', 'refunded'];

    public $status_color = ['dark', 'success', 'danger'];

    /**
     * Get the user record associated with the record.
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Get the book record associated with the record.
     */
    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }
}
