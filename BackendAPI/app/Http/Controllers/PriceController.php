<?php

namespace App\Http\Controllers;

abstract class PriceChartController
{
public function index() { 
    return Post::all(); 
    }

}