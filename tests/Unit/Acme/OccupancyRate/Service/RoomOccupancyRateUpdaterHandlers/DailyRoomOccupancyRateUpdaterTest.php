<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers;

use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers\DailyRoomOccupancyRateUpdater;
use Acme\Room\Model\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class DailyRoomOccupancyRateUpdaterTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccess(): void
    {
        $room = Room::create(10);
        $room->id = 1;
        $room->save();

        DB::table('daily_room_occupancy_rates')
            ->insert([
                'room_id'        => $room->id,
                'date'           => '2023-01-01 00:00:00',
                'rate'           => 0,
                'total_booked'   => 0,
                'total_blocked'  => 0,
                'total_capacity' => $room->capacity,
            ]);

        /** @var DailyRoomOccupancyRateUpdater $updater */
        $updater = $this->app->make(DailyRoomOccupancyRateUpdater::class);

        $bookingDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-01'),
            Carbon::createFromFormat('Y-m-d', '2023-01-02')
        ];
        $cancelledBookingDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-01'),
        ];
        $blockDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-04'),
            Carbon::createFromFormat('Y-m-d', '2023-01-05')
        ];
        $cancelledBlockDates = [
            Carbon::createFromFormat('Y-m-d', '2023-01-04'),
        ];

        $updater->update(new RoomOccupancyStatusChanges(
            $room,
            $bookingDates,
            $blockDates,
            $cancelledBookingDates,
            $cancelledBlockDates
        ));

        $this->assertDatabaseHas('daily_room_occupancy_rates', [
            'room_id'        => $room->id,
            'date'           => Carbon::createFromFormat('Y-m-d', '2023-01-01')->startOfDay(),
            'total_booked'   => 0,
            'total_blocked'  => 0,
            'total_capacity' => $room->capacity,
        ]);

        $this->assertDatabaseHas('daily_room_occupancy_rates', [
            'room_id'        => $room->id,
            'date'           => Carbon::createFromFormat('Y-m-d', '2023-01-02')->startOfDay(),
            'total_booked'   => 1,
            'total_blocked'  => 0,
            'total_capacity' => $room->capacity,
        ]);

        $this->assertDatabaseHas('daily_room_occupancy_rates', [
            'room_id'        => $room->id,
            'date'           => Carbon::createFromFormat('Y-m-d', '2023-01-04')->startOfDay(),
            'total_booked'   => 0,
            'total_blocked'  => 0,
            'total_capacity' => $room->capacity,
        ]);

        $this->assertDatabaseHas('daily_room_occupancy_rates', [
            'room_id'        => $room->id,
            'date'           => Carbon::createFromFormat('Y-m-d', '2023-01-05')->startOfDay(),
            'total_booked'   => 0,
            'total_blocked'  => 1,
            'total_capacity' => $room->capacity,
        ]);
    }
}
