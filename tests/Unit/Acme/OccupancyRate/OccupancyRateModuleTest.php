<?php

declare(strict_types=1);

namespace Tests\Unit\Acme\OccupancyRate;

use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Acme\OccupancyRate\Model\MonthlyRoomOccupancyRate;
use Acme\OccupancyRate\OccupancyRateModule;
use Acme\OccupancyRate\Repository\DailyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Repository\MonthlyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdater;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class OccupancyRateModuleTest extends TestCase
{
    public function testRegister(): void
    {
        $module = new OccupancyRateModule($this->app);

        $module->register();

        $dailyRepo = $this->app->make(DailyRoomOccupancyRateRepository::class);
        $monthlyRepo = $this->app->make(MonthlyRoomOccupancyRateRepository::class);

        $this->assertInstanceOf(DailyRoomOccupancyRateRepository::class, $dailyRepo);
        $this->assertInstanceOf(MonthlyRoomOccupancyRateRepository::class, $monthlyRepo);

        $this->assertEquals($this->getInaccessibleProperty($dailyRepo, 'modelClass'), DailyRoomOccupancyRate::class);
        $this->assertEquals($this->getInaccessibleProperty($monthlyRepo, 'modelClass'), MonthlyRoomOccupancyRate::class);

        $updater = $this->app->make(RoomOccupancyRateUpdater::class);

        $this->assertCount(2, $this->getInaccessibleProperty($updater, 'roomOccupancyRateUpdaters'));
    }
}
