<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Module;

use Component\Module\AbstractModule;
use Tests\Unit\Component\Module\Model\Sample;
use Tests\Unit\Component\Module\Repository\SampleRepository;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class SampleModule extends AbstractModule
{
    protected function registerRepositories(): array
    {
        return [
            Sample::class => SampleRepository::class,
        ];
    }
}
