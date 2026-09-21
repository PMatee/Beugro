<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
   public $timestamps = false;
    protected $fillable = ['time_block', 'state', 'message', 'success', 'created_at'];
    protected $casts = [
        'time_block' => 'datetime',
        'success'    => 'boolean',
        'created_at' => 'datetime',
    ];
   
}
