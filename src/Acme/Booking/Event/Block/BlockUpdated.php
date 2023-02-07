<?php

declare(strict_types=1);

namespace Acme\Booking\Event\Block;

use Acme\Booking\Model\Block;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChangesContract;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BlockUpdated implements RoomOccupancyStatusChangesContract
{
    public function __construct(
        protected Room   $room,
        protected Carbon $startsAt,
        protected Carbon $endsAt,
        protected Room   $previousRoom,
        protected Carbon $previousStartsAt,
        protected Carbon $previousEndsAt,
    ) {}

    public static function createFromBlock(Block $block, Block $previousBlock): static
    {
        return new static(
            $block->room,
            $block->startsAt,
            $block->endsAt,
            $previousBlock->room,
            $previousBlock->startsAt,
            $previousBlock->endsAt,
        );
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @return Carbon
     */
    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    /**
     * @return Carbon
     */
    public function getEndsAt(): Carbon
    {
        return $this->endsAt;
    }

    /**
     * @return Room
     */
    public function getPreviousRoom(): Room
    {
        return $this->previousRoom;
    }

    /**
     * @return Carbon
     */
    public function getPreviousStartsAt(): Carbon
    {
        return $this->previousStartsAt;
    }

    /**
     * @return Carbon
     */
    public function getPreviousEndsAt(): Carbon
    {
        return $this->previousEndsAt;
    }

    public function getChanges(): array
    {
        return [
            new RoomOccupancyStatusChanges(
                room      : $this->room,
                blockDates: CarbonPeriod::create(
                    $this->startsAt->toDateString(),
                    $this->endsAt->toDateString()
                )->toArray(),
            ),
            new RoomOccupancyStatusChanges(
                room               : $this->previousRoom,
                cancelledBlockDates: CarbonPeriod::create(
                    $this->previousStartsAt->toDateString(),
                    $this->previousEndsAt->toDateString()
                )->toArray(),
            ),
        ];
    }
}
