<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'name',
        'value',
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
        'value' => 'object',
    ];

    /**
     * Get Setting Value
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public static function getValue($name)
    {
        $data = Setting::where('name', $name)->first();
        if (!$data) {return false;}
        return $data->value;
    }
}
