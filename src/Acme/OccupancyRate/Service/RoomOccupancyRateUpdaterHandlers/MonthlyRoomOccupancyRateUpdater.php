<?php

declare(strict_types=1);

namespace Acme\OccupancyRate\Service\RoomOccupancyRateUpdaterHandlers;

use Acme\Booking\Repository\BlockRepository;
use Acme\Booking\Repository\BookingRepository;
use Acme\OccupancyRate\Contract\CalculateOccupancyRate;
use Acme\OccupancyRate\Contract\RoomOccupancyStatusChanges;
use Acme\OccupancyRate\Contract\RoomOccupancyRateUpdaterInterface;
use Acme\OccupancyRate\Model\MonthlyRoomOccupancyRate;
use Acme\OccupancyRate\Repository\MonthlyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Service\OccupancyRateCalculator;
use Shared\Carbon\CarbonHelper;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class MonthlyRoomOccupancyRateUpdater implements RoomOccupancyRateUpdaterInterface
{
    public function __construct(
        protected MonthlyRoomOccupancyRateRepository $repository,
        protected BookingRepository                  $bookingRepository,
        protected BlockRepository                    $blockRepository,
        protected OccupancyRateCalculator            $rateCalculator,
    ) {}

    public function update(RoomOccupancyStatusChanges $changes): void
    {
        $monthYears = CarbonHelper::getMonthYearsFromDates([
            ...$changes->getBookingDates(),
            ...$changes->getCancelledBookingDates(),
            ...$changes->getBlockDates(),
            ...$changes->getCancelledBlockDates()
        ]);

        /** @var MonthlyRoomOccupancyRate[] $occupancyRateMap */
        $occupancyRateMap = $this->repository->findByRoomAndMonthYears(
            $changes->getRoom()->getKey(),
            array_keys($monthYears)
        )->mapWithKeys(function (MonthlyRoomOccupancyRate $item) {
            return [$item->monthYear => $item];
        })->all();

        // Create daily room occupancy rates that do not exist
        foreach ($monthYears as $key => $item) {
            if (!isset($occupancyRateMap[$key])) {
                $occupancyRateMap[$key] = MonthlyRoomOccupancyRate::create(room: $changes->getRoom(), monthYear: $item);
            }
        }

        // Create monthly room occupancy rates that do not exist
        foreach ($changes->getBookingDates() as $date) {
            $occupancyRateMap[$date->format('Y-m')]->totalBooked += 1;
        }

        foreach ($changes->getCancelledBookingDates() as $date) {
            $occupancyRateMap[$date->format('Y-m')]->totalBooked -= 1;
        }

        foreach ($changes->getBlockDates() as $date) {
            $occupancyRateMap[$date->format('Y-m')]->totalBlocked += 1;
        }

        foreach ($changes->getCancelledBlockDates() as $date) {
            $occupancyRateMap[$date->format('Y-m')]->totalBlocked -= 1;
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
