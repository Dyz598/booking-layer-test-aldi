<?php

declare(strict_types=1);

namespace Acme\Booking;

use Acme\Booking\Model\Booking;
use Acme\Booking\Model\Block;
use Acme\Booking\Repository\BlockRepository;
use Acme\Booking\Repository\BookingRepository;
use Component\Module\AbstractModule;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class BookingModule extends AbstractModule
{
    protected function registerRepositories(): array
    {
        return [
            Booking::class => BookingRepository::class,
            Block::class   => BlockRepository::class,
        ];
    }
}
