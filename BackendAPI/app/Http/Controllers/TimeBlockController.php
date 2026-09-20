<?php

namespace App\Http\Controllers;


use App\Services\TimeBlockService;
use Illuminate\Http\Request;


class TimeBlockController extends Controller
{
    public function index(TimeBlockService $blocks)
    {
        return $blocks->all();
    }

    public function store(Request $request, TimeBlockService $prices)
    {
        $data = $request->validate([
            'blocks'   => ['required', 'array', 'min:2'],   // min 30 min = 2 blocks
            'blocks.*' => ['date'],
        ]);

        return $prices->save($data['blocks']);
    }
}