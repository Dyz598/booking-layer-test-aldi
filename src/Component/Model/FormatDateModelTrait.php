<?php

declare(strict_types=1);

namespace Component\Model;

use Carbon\Carbon;
use DateTimeInterface;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
trait FormatDateModelTrait
{
    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        if ($date instanceof Carbon && $date->isStartOfDay()) {
            return $date->format('Y-m-d');
        }

        return $date->format('Y-m-d H:i:s');
    }
}
