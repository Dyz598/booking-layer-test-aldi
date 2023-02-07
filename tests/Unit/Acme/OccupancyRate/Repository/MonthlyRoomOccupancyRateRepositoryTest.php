<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate\Repository;

use Acme\OccupancyRate\Repository\MonthlyRoomOccupancyRateRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class MonthlyRoomOccupancyRateRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSumByMonthYearAndRoomIds(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', '2023-01-01');

        DB::table('rooms')->insert(['id' => 1, 'capacity' => 10]);
        DB::table('rooms')->insert(['id' => 2, 'capacity' => 5]);
        DB::table('monthly_room_occupancy_rates')->insert([
            'room_id'        => 1,
            'month'          => $date->month,
            'year'           => $date->year,
            'rate'           => 0.5,
            'total_booked'   => 25,
            'total_blocked'  => 5,
            'total_capacity' => 10 * $date->daysInMonth,
            'month_year'     => $date->format('Y-m'),
        ]);
        DB::table('monthly_room_occupancy_rates')->insert([
            'room_id'        => 2,
            'month'          => $date->month,
            'year'           => $date->year,
            'rate'           => 0.5,
            'total_booked'   => 10,
            'total_blocked'  => 2,
            'total_capacity' => 5 * $date->daysInMonth,
            'month_year'     => $date->format('Y-m'),
        ]);

        /** @var MonthlyRoomOccupancyRateRepository $repo */
        $repo = $this->app->make(MonthlyRoomOccupancyRateRepository::class);

        $result = $repo->sumByMonthYearAndRoomIds($date->month, $date->year, [1, 2]);

        $this->assertEquals(35, $result->total_booked);
        $this->assertEquals(7, $result->total_blocked);
    }

    public function testFindByRoomAndMonthYears(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', '2023-01-01');
        $date2 = Carbon::createFromFormat('Y-m-d', '2023-02-01');

        DB::table('rooms')->insert(['id' => 1, 'capacity' => 10]);
        DB::table('monthly_room_occupancy_rates')->insert([
            'room_id'        => 1,
            'month'          => $date->month,
            'year'           => $date->year,
            'rate'           => 0.5,
            'total_booked'   => 15,
            'total_blocked'  => 4,
            'total_capacity' => 10 * $date->daysInMonth,
            'month_year'     => $date->format('Y-m'),
        ]);
        DB::table('monthly_room_occupancy_rates')->insert([
            'room_id'        => 1,
            'month'          => $date2->month,
            'year'           => $date2->year,
            'rate'           => 0.5,
            'total_booked'   => 20,
            'total_blocked'  => 7,
            'total_capacity' => 10 * $date2->daysInMonth,
            'month_year'     => $date2->format('Y-m'),
        ]);

        /** @var MonthlyRoomOccupancyRateRepository $repo */
        $repo = $this->app->make(MonthlyRoomOccupancyRateRepository::class);

        $result = $repo->findByRoomAndMonthYears(1, [$date->format('Y-m'), $date2->format('Y-m')]);

        $this->assertEquals(2, $result->count());
    }
}
