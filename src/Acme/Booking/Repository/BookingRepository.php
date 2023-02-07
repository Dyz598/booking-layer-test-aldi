<?php

declare(strict_types=1);

namespace Acme\Booking\Repository;

use Carbon\Carbon;
use Component\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingRepository extends Repository
{
    public function countByRoomAndOverlapsInnerDate(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBookingId) {
            $query->whereNot('id', $excludeBookingId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('starts_at', '>=', $startDate)
            ->where('ends_at', '<=', $endDate);

        return $query->count();
    }

    public function countByRoomAndOverlapsOuterDate(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBookingId) {
            $query->whereNot('id', $excludeBookingId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('starts_at', '<=', $startDate)
            ->where('ends_at', '>=', $endDate);

        return $query->count();
    }

    public function countByRoomAndOverlapLeftOuter(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBookingId) {
            $query->whereNot('id', $excludeBookingId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('starts_at', '>=', $startDate)
            ->where('starts_at', '<=', $endDate);

        return $query->count();
    }

    public function countByRoomAndOverlapRightOuter(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBookingId) {
            $query->whereNot('id', $excludeBookingId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('ends_at', '>=', $startDate)
            ->where('ends_at', '<=', $endDate);

        return $query->count();
    }
}
