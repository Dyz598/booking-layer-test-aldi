<?php

namespace App\Observers;

use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Illuminate\Support\Facades\Cache;

class DailyRoomOccupancyRateObserver
{
    public bool $afterCommit = true;

    public function saved(DailyRoomOccupancyRate $model)
    {
        Cache::tags(sprintf('daily_occupancy_rates_%s', $model->date->format('Y-m-d')))->flush();
    }
}
