<?php

namespace App\Listeners;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChangesContract;
use App\Jobs\UpdateRoomOccupancyRates;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessRoomOccupancyStatusChanges implements ShouldQueue
{
    public bool $afterCommit = true;

    public function handle(RoomOccupancyStatusChangesContract $event)
    {
        foreach ($event->getChanges() as $changes) {
            UpdateRoomOccupancyRates::dispatch($changes)
                ->afterCommit();
        }
    }
}
