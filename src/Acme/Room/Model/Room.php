<?php

declare(strict_types=1);

namespace Acme\Room\Model;

use Component\Model\AbstractModel;
use Component\Model\FormatDateModelTrait;

/**
 * @property int $id
 * @property int $capacity
 *
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class Room extends AbstractModel
{
    use FormatDateModelTrait;

    public static function create(int $capacity): static
    {
        $static = new static();
        $static->capacity = $capacity;

        return $static;
    }
}
