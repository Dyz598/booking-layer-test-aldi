<?php

declare(strict_types=1);

namespace Component\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
abstract class AbstractModel extends Model
{
    public function setAttribute($key, $value): ?static
    {
        return parent::setAttribute(Str::snake($key), $value);
    }

    public function getAttribute($key): mixed
    {
        return parent::getAttribute(Str::snake($key));
    }
}
