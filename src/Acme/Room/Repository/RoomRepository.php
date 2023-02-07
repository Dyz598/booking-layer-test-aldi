<?php

declare(strict_types=1);

namespace Acme\Room\Repository;

use Component\Repository\Repository;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomRepository extends Repository
{
    public function sumCapacity(?array $ids = null): int
    {
        $query = $this->createQueryBuilder();

        if (filled($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->sum('capacity');
    }
}
