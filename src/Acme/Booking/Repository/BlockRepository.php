<?php

declare(strict_types=1);

namespace Acme\Booking\Repository;

use Carbon\Carbon;
use Component\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockRepository extends Repository
{
    public function countByRoomAndOverlapsInnerDate(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBlockId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBlockId) {
            $query->whereNot('id', $excludeBlockId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('starts_at', '>=', $startDate)
            ->where('ends_at', '<=', $endDate);

        return $query->count();
    }

    public function countByRoomAndOverlapsOuterDate(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBlockId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBlockId) {
            $query->whereNot('id', $excludeBlockId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('starts_at', '<=', $startDate)
            ->where('ends_at', '>=', $endDate);

        return $query->count();
    }

    public function countByRoomAndOverlapLeftOuter(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBlockId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBlockId) {
            $query->whereNot('id', $excludeBlockId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('starts_at', '>=', $startDate)
            ->where('starts_at', '<=', $endDate);

        return $query->count();
    }

    public function countByRoomAndOverlapRightOuter(int $roomId, Carbon $startDate, Carbon $endDate, ?int $excludeBlockId = null): int
    {
        $query = $this->createQueryBuilder();

        if (null !== $excludeBlockId) {
            $query->whereNot('id', $excludeBlockId);
        }

        $query
            ->where('room_id', $roomId)
            ->where('ends_at', '>=', $startDate)
            ->where('ends_at', '<=', $endDate);

        return $query->count();
    }
}
