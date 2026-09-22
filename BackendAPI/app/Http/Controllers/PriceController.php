<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Services\PriceService;

class PriceController extends Controller
{
    public function index(PriceService $prices)
    {
        $prices->ensureFresh();

        return response()->json( [
            'unit' => 'HUF/kWh',
            'average' => $prices->average(),
            'prices' => $prices->today(),
        ]);
    }
}