<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Carbon;

use Carbon\Carbon;
use Shared\Carbon\CarbonHelper;
use Tests\TestCase;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class CarbonHelperTest extends TestCase
{
    public function testSuccess(): void
    {
        $results = CarbonHelper::getMonthYearsFromDates([
            now()->setDay(1)->setMonth(1)->setYear(2023),
            now()->setDay(1)->setMonth(1)->setYear(2023),
            now()->setDay(1)->setMonth(2)->setYear(2023),
        ]);

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertTrue(isset($results['2023-01']));
        $this->assertTrue(isset($results['2023-02']));
        $this->assertTrue($results['2023-01'] instanceof Carbon);
        $this->assertTrue($results['2023-02'] instanceof Carbon);
    }
}
