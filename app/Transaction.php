<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tracking', 'name', 'from', 'to', 'amount', 'paid_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'amount' => 'integer',
    ];
}
