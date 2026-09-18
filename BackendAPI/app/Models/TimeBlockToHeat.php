<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeBlockToHeat extends Model
{
    protected $table = 'time_blocks_to_heat';
    public $timestamps = false;
    protected $fillable = ['timeblock'];
    protected $casts = [
        'time_block' => 'datetime', 
        ];
   
}
