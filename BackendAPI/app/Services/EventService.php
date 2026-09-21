<?php


namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Process;

class EventService
{
    public function switch(string $state, ?Carbon $slot = null): Event{
        $config = config('services.boiler');

        try {
            $result = Process::timeout(10)->run([
                $config['exe'],
                '--user=' . $config['user'],
                '--password=' . $config['password'],
                '--switch=' . $state,
            ]);
            $message = trim($result->output() . $result->errorOutput());
            $success = $result->successful();
        } catch (\Throwable $e) {
            // exe missing, timeout, etc.
            $message = 'Could not run the boiler program: ' . $e->getMessage();
            $success = false;
        }

        return Event::create([
            'time_block' => $slot,
            'state'      => $state,
            'message'    => $message,
            'success'    => $success,
            'created_at' => now(),
        ]);
    }
    

}