<?php

namespace App\Observers;

use Acme\OccupancyRate\Model\MonthlyRoomOccupancyRate;
use Illuminate\Support\Facades\Cache;

class MonthlyRoomOccupancyRateObserver
{
    public bool $afterCommit = true;

    public function saved(MonthlyRoomOccupancyRate $model)
    {
        Cache::tags(sprintf('monthly_occupancy_rates_%s', $model->monthYear))->flush();
    }
}
