<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Model;

use Acme\Room\Model\Room;
use Acme\Room\Relation\BelongsToRoomTrait;
use Carbon\Carbon;
use Component\Model\AbstractModel;
use Component\Model\FormatDateModelTrait;

/**
 * @property Carbon $date
 * @property float  $rate
 * @property int    $totalBooked
 * @property int    $totalBlocked
 * @property int    $totalCapacity
 *
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class DailyRoomOccupancyRate extends AbstractModel
{
    use FormatDateModelTrait,
        BelongsToRoomTrait;

    protected $casts = [
        'date' => 'date',
    ];

    protected $attributes = [
        'total_booked'  => 0,
        'total_blocked' => 0,
    ];

    public static function create(
        Room   $room,
        Carbon $date,
    ): static
    {
        $static = new static();
        $static->date = $date;
        $static->totalCapacity = $room->capacity;

        $static->room()->associate($room);

        return $static;
    }
}
