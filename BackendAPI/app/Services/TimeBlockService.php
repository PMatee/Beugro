<?php

namespace App\Services;

use App\Models\Price;
use App\Models\TimeBlockToHeat;
use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TimeBlockService
{
    public function __construct(private PriceService $prices){

    }

    public function all(): Collection
    {
        return TimeBlockToHeat::orderBy('time_block')
            ->get()
            ->map(fn ($b) => $b->time_block->toIso8601String());
    }

    public function save(array $times): array
    {

        $blocks = $this->parse($times);
        $this->checkRules($blocks);

        $rows = $blocks
            ->map(fn ($b) => ['time_block' => $b->format('Y-m-d H:i:s')])
            ->all();

            DB::transaction(function () use ($rows) {
                TimeBlockToHeat::query()->delete();
                TimeBlockToHeat::insert($rows);
            });

            return [
            'saved' => $blocks->count(),
            'above_average_blocks' => $this->aboveAverage($rows),
        ];

        $data = $request->validate([
            'blocks'   => ['required', 'array', 'min:2'],   // min 30 min = 2 blocks
            'blocks.*' => ['date'],
        ]);
    }

    private function parse(array $times): Collection
    {

        $timezone = config('app.timezone');

        return collect($times)
            ->map(fn ($t) => Carbon::parse($t)->setTimezone($timezone))
            ->unique(fn ($t) => $t->timestamp)
            ->values();
    }

    private function checkRules(Collection $blocks): void
    {

        if ($blocks->count() < 2) {
            throw ValidationException::withMessages([
                'blocks' => 'Select at least 30 minutes (2 blocks).',
            ]);
        }

        
        if ($blocks->contains(fn ($b) => $b->hour === 23)) {
            throw ValidationException::withMessages([
                'blocks' => 'The 23:00-00:00 period is not allowed.',
            ]);
        }
    }


    private function aboveAverage(array $rows): Collection
    {

        $this->prices->ensureFresh();
        $avg = $this->prices->average();

        return Price::whereIn('timestamp', collect($rows)->pluck('time_block'))
            ->where('price_huf_kwh', '>', $avg)
            ->orderBy('timestamp')
            ->get()
            ->map(fn ($p) => $p->timestamp->toIso8601String())
            ->values();

        
    }
}