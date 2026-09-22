<?php

namespace App\Services;

use App\Models\Price;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PriceService
{
    
public function sync(): int
{
    $response = Http::withToken(config('services.energy_charts.token'))
        ->acceptJson()
        ->get('https://api.energy-charts.info/v2/price', ['bzn' => 'HU'])
        ->throw()
        ->json();

        
    if (!is_array($response) || ! isset($response['data']) || ! is_array($response['data'] )) {
        throw new \RuntimeException(
            'Unexpected price API shape.'
        );
    }

    $rate = config('services.energy_charts.eur_huf');
    if (! is_numeric($rate) || $rate <= 0) {
    throw new \RuntimeException('services.energy_charts.eur_huf is not configured.');
}

    $timezone   = config('app.timezone');

    $rows = collect($response['data'])
        ->map(function (array $item) use ($timezone, $rate) {
            $price = $item['values']['day_ahead_price'] ?? null;

            if($price === null || !is_numeric($price)){
                return null;
            }

            $priceEurMwh = (float) $price;
            $priceHufKwh = ($priceEurMwh / 1000)*$rate;

            return [
                'timestamp' => Carbon::parse($item['timestamp'])->setTimezone($timezone),
                'price_eur_mwh' => round($priceEurMwh, 6),
                'price_huf_kwh' => round($priceHufKwh, 2),                                   
                
            ];
        })
        ->filter()
        ->values()
        ->all();
    

    Price::upsert(
        $rows, 
        ['timestamp'], 
        ['price_eur_mwh', 'price_huf_kwh']);

    return count($rows);
}
public function ensureFresh(): void
    {
        $today = Carbon::today(config('app.timezone'));
    
        if (! Price::whereDate('timestamp', $today)->exists()) {
            $this->sync();
        }
    }

    public function average(): float
    {
        $today = Carbon::today(config('app.timezone'));

        return round((float) Price::whereDate('timestamp', $today)->avg('price_huf_kwh'),2);
    }

    public function today(): array{
        $today = Carbon::today(config('app.timezone'));

        return Price::query()
        ->whereDate('timestamp', $today)
        ->orderBy('timestamp')
        ->get()
        ->map(fn (Price $price) => [
            'time' => $price->timestamp->toIso8601String(),
            'eur_per_kwh' => (float) round($price->price_eur_mwh/1000,6),
            'huf_per_kwh' => (float) $price->price_huf_kwh,
        ])
        ->values()
        ->all();
    }

    public function thirtyMinuteBlocks($prices): array{
        return collect($prices)
        ->groupBy(function (Price $prices){
            $timestamp = $prices['timestamp']->copy();

            $minute = floor($timestamp->minute / 30) * 30;

            return $timestamp
            ->minute($minute)
            ->second(0)
            ->format('Y-m-d H:i:s');
        })
        ->map(function ($prices, string $timestamp){
            return [
                'timestamp' => $timestamp,
                'price_eur_kwh' => round($prices->avg('price_eur_mwh')/1000,6),
                'price_huf_kwh' => round($prices->avg('price_huf_kwh'),2),
            ];
        })
        ->values()
        ->all();
    }

    public function getThirtyMinutePrices(): array{
        $today = Carbon::today(config('app.timezone'));

        $prices = Price::query()
        ->whereDate('timestamp', $today)
        ->orderBy('timestamp')
        ->get();

        return $this->thirtyMinuteBlocks($prices);
    }
}