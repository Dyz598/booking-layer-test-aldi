<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers\MonthlyRoomOccupancyRateUpdater;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class MonthlyRoomOccupancyRateUpdaterTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccess(): void
    {
        $room = Room::create(10);
        $room->id = 1;
        $room->save();

        DB::table('monthly_room_occupancy_rates')
            ->insert([
                'room_id'        => $room->id,
                'month'          => 1,
                'year'           => 2023,
                'rate'           => 0,
                'total_booked'   => 0,
                'total_blocked'  => 0,
                'total_capacity' => $room->capacity * 31,
                'month_year'     => '2023-01',
            ]);

        /** @var MonthlyRoomOccupancyRateUpdater $updater */
        $updater = $this->app->make(MonthlyRoomOccupancyRateUpdater::class);

        $bookingDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-01'),
            Carbon::createFromFormat('Y-m-d', '2023-01-02')
        ];
        $cancelledBookingDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-01'),
        ];
        $blockDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-01'),
            Carbon::createFromFormat('Y-m-d', '2023-02-01')
        ];
        $cancelledBlockDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-01'),
        ];

        $updater->update(new RoomOccupancyStatusChanges(
            $room,
            $bookingDates,
            $blockDates,
            $cancelledBookingDates,
            $cancelledBlockDates
        ));

        $this->assertDatabaseHas('monthly_room_occupancy_rates', [
            'room_id'        => $room->id,
            'month'          => 1,
            'year'           => 2023,
            'total_booked'   => 1,
            'total_blocked'  => 0,
            'total_capacity' => $room->capacity * 31,
            'month_year'     => '2023-01',
        ]);

        $this->assertDatabaseHas('monthly_room_occupancy_rates', [
            'room_id'        => $room->id,
            'month'          => 2,
            'year'           => 2023,
            'total_booked'   => 0,
            'total_blocked'  => 1,
            'total_capacity' => $room->capacity * 28,
            'month_year'     => '2023-02',
        ]);
    }
}
