<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Repository;

use Acme\OccupancyRate\Repository\DailyRoomOccupancyRateRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class DailyRoomOccupancyRateRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSumByDateAndRoomIds(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', '2023-01-01');

        DB::table('rooms')->insert(['id' => 1, 'capacity' => 10]);
        DB::table('rooms')->insert(['id' => 2, 'capacity' => 5]);
        DB::table('daily_room_occupancy_rates')->insert([
            'room_id'        => 1,
            'date'           => $date->toDateString(),
            'rate'           => 0.5,
            'total_booked'   => 2,
            'total_blocked'  => 1,
            'total_capacity' => 10,
        ]);
        DB::table('daily_room_occupancy_rates')->insert([
            'room_id'        => 2,
            'date'           => $date->toDateString(),
            'rate'           => 0.5,
            'total_booked'   => 1,
            'total_blocked'  => 0,
            'total_capacity' => 5,
        ]);

        /** @var DailyRoomOccupancyRateRepository $repo */
        $repo = $this->app->make(DailyRoomOccupancyRateRepository::class);

        $result = $repo->sumByDateAndRoomIds($date, [1, 2]);

        $this->assertEquals(3, $result->total_booked);
        $this->assertEquals(1, $result->total_blocked);
    }

    public function testFindByRoomAndDates(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', '2023-01-01');
        $date2 = Carbon::createFromFormat('Y-m-d', '2023-01-02');

        DB::table('rooms')->insert(['id' => 1, 'capacity' => 10]);
        DB::table('daily_room_occupancy_rates')->insert([
            'room_id'        => 1,
            'date'           => $date->toDateString(),
            'rate'           => 0.5,
            'total_booked'   => 4,
            'total_blocked'  => 1,
            'total_capacity' => 10,
        ]);
        DB::table('daily_room_occupancy_rates')->insert([
            'room_id'        => 1,
            'date'           => $date2->toDateString(),
            'rate'           => 0.5,
            'total_booked'   => 2,
            'total_blocked'  => 1,
            'total_capacity' => 10,
        ]);

        /** @var DailyRoomOccupancyRateRepository $repo */
        $repo = $this->app->make(DailyRoomOccupancyRateRepository::class);

        $result = $repo->findByRoomAndDates(1, [$date->format('Y-m-d'), $date2->format('Y-m-d')]);

        $this->assertEquals(2, $result->count());
    }
}
