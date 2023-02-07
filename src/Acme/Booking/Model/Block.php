<?php

declare(strict_types=1);

namespace Acme\Booking\Model;

use Acme\Room\Model\Room;
use Acme\Room\Relation\BelongsToRoomTrait;
use Carbon\Carbon;
use Component\Model\AbstractModel;
use Component\Model\FormatDateModelTrait;

/**
 * @property Carbon $startsAt
 * @property Carbon $endsAt
 *
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class Block extends AbstractModel
{
    use FormatDateModelTrait,
        BelongsToRoomTrait;

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public static function create(
        Room   $room,
        Carbon $startsAt,
        Carbon $endsAt,
    ): static
    {
        $booking = new static();
        $booking->startsAt = $startsAt;
        $booking->endsAt = $endsAt;

        $booking->room()->associate($room);

        return $booking;
    }
}
