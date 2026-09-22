<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConsumptionController extends Controller
{
    private const BOILER_KW = 10;

    public function index(Request $request)
    {
        $date = Carbon::parse($request->query('date', today()), config('app.timezone'));

        $onEvents = Event::where('state', 'on')
            ->where('success', true)
            ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
            ->get();

        $byHour = $onEvents
            ->groupBy(fn (Event $e) => $e->created_at->format('H'))
            ->map(fn ($minutes) => round($minutes->count() * self::BOILER_KW / 60, 2));

        $hours = collect(range(0, 23))->map(fn ($h) => [
            'hour' => sprintf('%02d:00', $h),
            'kwh' => $byHour[sprintf('%02d', $h)] ?? 0,
        ]);

        return response()->json([
            'date' => $date->toDateString(),
            'unit' => 'kWh',
            'total_kwh' => round($hours->sum('kwh'), 2),
            'hours' => $hours->values(),
        ]);
    }
}