<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'price_chart';
    public $timestamps = false;
    protected $fillable = ['timestamp','price_eur_mwh','price_huf_kwh'];
    protected $casts = [
        'timestamp' => 'datetime', 
        'price_eur_mwh' => 'decimal:6',
        'price_huf_kwh' => 'decimal:2'
        ];
   
}
