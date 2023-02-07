<?php

declare(strict_types=1);

namespace Acme\OccupancyRate;

use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Acme\OccupancyRate\Model\MonthlyRoomOccupancyRate;
use Acme\OccupancyRate\Repository\DailyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Repository\MonthlyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdater;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers\DailyRoomOccupancyRateUpdater;
use Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers\MonthlyRoomOccupancyRateUpdater;
use Component\Module\AbstractModule;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class OccupancyRateModule extends AbstractModule
{
    public function register()
    {
        $this->app->singleton(RoomOccupancyRateUpdater::class, function ($app) {
            return new RoomOccupancyRateUpdater([
                $app->make(DailyRoomOccupancyRateUpdater::class),
                $app->make(MonthlyRoomOccupancyRateUpdater::class),
            ]);
        });

        parent::register();
    }

    protected function registerRepositories(): array
    {
        return [
            DailyRoomOccupancyRate::class   => DailyRoomOccupancyRateRepository::class,
            MonthlyRoomOccupancyRate::class => MonthlyRoomOccupancyRateRepository::class,
        ];
    }
}
