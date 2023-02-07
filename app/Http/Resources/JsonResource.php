<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as LaravelJsonResource;

class JsonResource extends LaravelJsonResource
{
    public function toArray($request)
    {
        return [
            'data' => $this->resource,
            'meta' => [
                'client_ip' => $request->getClientIp(),
                'hostname'  => gethostname(),
            ],
        ];
    }
}
