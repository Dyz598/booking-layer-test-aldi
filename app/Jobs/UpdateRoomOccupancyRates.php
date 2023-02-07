<?php

namespace App\Jobs;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class UpdateRoomOccupancyRates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(protected RoomOccupancyStatusChanges $changes) {}

    public function handle(RoomOccupancyRateUpdater $updater)
    {
        $updater->update($this->changes);
    }

    public function middleware()
    {
        if (!app()->environment('local')) {
            return [
                (new WithoutOverlapping($this->changes->getRoom()->getKey()))
                    ->releaseAfter(5)
                    ->expireAfter(5 * 5)
            ];
        }

        return [];
    }
}
