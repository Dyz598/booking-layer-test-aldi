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
class MonthlyRoomOccupancyRateRepository extends Repository
{
    public function sumByMonthYearAndRoomIds(int $month, int $year, ?array $roomIds): object
    {
        $query = $this->createRawQueryBuilder();

        $query->select([
            DB::raw('SUM(total_booked) as total_booked'),
            DB::raw('SUM(total_blocked) as total_blocked'),
        ])
            ->where('month', $month)
            ->where('year', $year);

        if (filled($roomIds)) {
            $query->whereIn('room_id', $roomIds);
        }

        return $query->first();
    }

    public function findByRoomAndMonthYears(int $roomId, array $monthYears): Collection
    {
        $query = $this->createQueryBuilder();

        $query->whereIn('month_year', $monthYears)
            ->where('room_id', $roomId);

        return $query->get();
    }
}
