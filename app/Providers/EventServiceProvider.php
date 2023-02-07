<?php

namespace App\Providers;

use Acme\Booking\Event\Block\BlockCreated;
use Acme\Booking\Event\Block\BlockUpdated;
use Acme\Booking\Event\Booking\BookingCreated;
use Acme\Booking\Event\Booking\BookingUpdated;
use Acme\OccupancyRate\Model\DailyRoomOccupancyRate;
use Acme\OccupancyRate\Model\MonthlyRoomOccupancyRate;
use App\Listeners\ProcessRoomOccupancyStatusChanges;
use App\Observers\DailyRoomOccupancyRateObserver;
use App\Observers\MonthlyRoomOccupancyRateObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        BookingCreated::class => [
            ProcessRoomOccupancyStatusChanges::class,
        ],
        BookingUpdated::class => [
            ProcessRoomOccupancyStatusChanges::class,
        ],
        BlockCreated::class   => [
            ProcessRoomOccupancyStatusChanges::class,
        ],
        BlockUpdated::class   => [
            ProcessRoomOccupancyStatusChanges::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        DailyRoomOccupancyRate::observe(DailyRoomOccupancyRateObserver::class);
        MonthlyRoomOccupancyRate::observe(MonthlyRoomOccupancyRateObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
