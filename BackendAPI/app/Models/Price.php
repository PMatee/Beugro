<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'price_chart';
    public $timestamps = false;
    protected $fillable = ['timestamp','price_eur','price_huf'];
    protected $casts = [
        'timestamp' => 'datetime', 
        'price_eur' => 'decimal:6',
        'price_huf' => 'decimal:2'
        ];
   
}
