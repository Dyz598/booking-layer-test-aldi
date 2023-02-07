<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Model;

use Carbon\Carbon;
use Component\Model\FormatDateModelTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class FormatDateModelTraitTest extends TestCase
{
    public function testMethodReturnsCorrectValues(): void
    {
        $stub = $this->getMockForTrait(FormatDateModelTrait::class);

        $reflection = new ReflectionClass(get_class($stub));
        $method = $reflection->getMethod('serializeDate');
        $method->setAccessible(true);

        $dateTime = '2023-01-05 12:25:40';
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
        $result1 = $method->invokeArgs($stub, [$carbon]);
        $result2 = $method->invokeArgs($stub, [$carbon->copy()->startOfDay()]);

        $this->assertEquals($result1, $dateTime);
        $this->assertEquals($result2, '2023-01-05');
    }
}
