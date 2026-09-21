<?php

namespace App\Console\Commands;

use App\Models\TimeBlockToHeat;
use App\Services\EventService;
use Illuminate\Console\Command;

class BoilerCheck extends Command
{
    protected $signature = 'boiler:check';
    protected $description = 'Switch the boiler on or off based on the saved time blocks';

    public function handle(EventService $boiler): int
    {
        $now = now();

        
        $slot = $now->copy()->minute(intdiv($now->minute, 15) * 15)->second(0);

        $shouldHeat = TimeBlockToHeat::where('time_block', $slot->format('Y-m-d H:i:s'))->exists();

        $event = $boiler->switch($shouldHeat ? 'on' : 'off', $slot);

        $this->info($slot->format('H:i') . ' -> ' . $event->state . ': ' . $event->message);

        return $event->success ? self::SUCCESS : self::FAILURE;
    }
}