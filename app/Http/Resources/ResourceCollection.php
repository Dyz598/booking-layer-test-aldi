<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as LaravelResourceCollection;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
class ResourceCollection extends LaravelResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'client_ip' => $request->getClientIp(),
                'hostname'  => gethostname(),
            ],
        ];
    }
}
