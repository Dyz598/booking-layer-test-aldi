<?php

declare(strict_types=1);

namespace Acme\Room\Relation;

use Acme\Room\Model\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 *
 * @property Room $room
 *
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
trait BelongsToRoomTrait
{
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
