<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers;

use Acme\Booking\Repository\BlockRepository;
use Acme\Booking\Repository\BookingRepository;
use Acme\OccupancyRate\Contract\CalculateOccupancyRate;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Contract\RoomOccupancyRateUpdaterInterface;
use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Acme\OccupancyRate\Repository\DailyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Service\OccupancyRateCalculator;
use Carbon\Carbon;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class DailyRoomOccupancyRateUpdater implements RoomOccupancyRateUpdaterInterface
{
    public function __construct(
        protected DailyRoomOccupancyRateRepository $repository,
        protected BookingRepository                $bookingRepository,
        protected BlockRepository                  $blockRepository,
        protected OccupancyRateCalculator          $rateCalculator,
    ) {}

    public function update(RoomOccupancyStatusChanges $changes): void
    {
        /** @var Carbon[] $dates */
        $dates = collect([
            ...$changes->getBookingDates(),
            ...$changes->getCancelledBookingDates(),
            ...$changes->getBlockDates(),
            ...$changes->getCancelledBlockDates()
        ])->mapWithKeys(function (Carbon $date) {
            return [$date->toDateString() => $date->startOfDay()];
        })->all();

        /** @var DailyRoomOccupancyRate[] $occupancyRateMap */
        $occupancyRateMap = $this->repository->findByRoomAndDates(
            roomId: $changes->getRoom()->getKey(),
            dates : collect($dates)->map(fn (Carbon $item) => $item->format('Y-m-d'))->all(),
        )->mapWithKeys(function (DailyRoomOccupancyRate $item) {
            return [$item->date->toDateString() => $item];
        })->all();

        // Create daily room occupancy rates that do not exist
        foreach ($dates as $date) {
            if (!isset($occupancyRateMap[$date->toDateString()])) {
                $occupancyRateMap[$date->toDateString()] = DailyRoomOccupancyRate::create($changes->getRoom(), $date);
            }
        }

        foreach ($changes->getBookingDates() as $date) {
            $occupancyRateMap[$date->toDateString()]->totalBooked += 1;
        }

        foreach ($changes->getCancelledBookingDates() as $date) {
            $occupancyRateMap[$date->toDateString()]->totalBooked -= 1;
        }

        foreach ($changes->getBlockDates() as $date) {
            $occupancyRateMap[$date->toDateString()]->totalBlocked += 1;
        }

        foreach ($changes->getCancelledBlockDates() as $date) {
            $occupancyRateMap[$date->toDateString()]->totalBlocked -= 1;
        }

        foreach ($occupancyRateMap as $occupancyRate) {
            $occupancyRate->rate = $this->rateCalculator->calculate(new CalculateOccupancyRate(
                totalBooked  : $occupancyRate->totalBooked,
                totalCapacity: $occupancyRate->totalCapacity,
                totalBlocked : $occupancyRate->totalBlocked,
            ));

            $this->repository->save($occupancyRate);
        }
    }
}
