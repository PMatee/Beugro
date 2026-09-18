<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    public $timestamps = false;
    protected $fillable = ['state','message','success'];
    protected $casts = [
        'success' => 'boolean'
        ];
   
}
