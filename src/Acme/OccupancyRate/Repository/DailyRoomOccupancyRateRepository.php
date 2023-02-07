<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Repository;

use Carbon\Carbon;
use Component\Repository\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class DailyRoomOccupancyRateRepository extends Repository
{
    public function sumByDateAndRoomIds(Carbon $date, ?array $roomIds): object
    {
        $query = $this->createRawQueryBuilder();

        $query->select([
            DB::raw('SUM(total_booked) as total_booked'),
            DB::raw('SUM(total_blocked) as total_blocked'),
        ])
            ->whereDate('date', $date);

        if (filled($roomIds)) {
            $query->whereIn('room_id', $roomIds);
        }

        return $query->first();
    }

    public function findByRoomAndDates(int $roomId, array $dates): Collection
    {
        $query = $this->createQueryBuilder();

        $query->whereIn('date', $dates)
            ->where('room_id', $roomId);

        return $query->get();
    }
}
