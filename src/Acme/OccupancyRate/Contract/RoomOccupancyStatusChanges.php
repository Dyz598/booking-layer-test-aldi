<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Contract;

use Acme\Room\Model\Room;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class RoomOccupancyStatusChanges
{
    public function __construct(
        protected Room  $room,
        protected array $bookingDates = [],
        protected array $blockDates = [],
        protected array $cancelledBookingDates = [],
        protected array $cancelledBlockDates = [],
    ) {}

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @return Carbon[]
     */
    public function getBookingDates(): array
    {
        return $this->bookingDates;
    }

    /**
     * @return Carbon[]
     */
    public function getBlockDates(): array
    {
        return $this->blockDates;
    }

    /**
     * @return Carbon[]
     */
    public function getCancelledBookingDates(): array
    {
        return $this->cancelledBookingDates;
    }

    /**
     * @return Carbon[]
     */
    public function getCancelledBlockDates(): array
    {
        return $this->cancelledBlockDates;
    }
}
