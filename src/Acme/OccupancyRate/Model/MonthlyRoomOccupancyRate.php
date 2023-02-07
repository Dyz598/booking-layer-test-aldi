<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Model;

use Acme\Room\Model\Room;
use Acme\Room\Relation\BelongsToRoomTrait;
use Carbon\Carbon;
use Component\Model\AbstractModel;
use Component\Model\FormatDateModelTrait;

/**
 * @property int    $month
 * @property int    $year
 * @property int    $totalBooked
 * @property int    $totalBlocked
 * @property int    $totalCapacity
 * @property float  $rate
 * @property string $monthYear
 *
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class MonthlyRoomOccupancyRate extends AbstractModel
{
    use FormatDateModelTrait,
        BelongsToRoomTrait;

    protected $attributes = [
        'total_booked'  => 0,
        'total_blocked' => 0,
    ];

    public static function create(
        Room   $room,
        Carbon $monthYear,
    ): static
    {
        $static = new static();
        $static->month = $monthYear->month;
        $static->year = $monthYear->year;
        $static->monthYear = $monthYear->format('Y-m');
        $static->totalCapacity = $room->capacity * $monthYear->daysInMonth;

        $static->room()->associate($room);

        return $static;
    }
}
