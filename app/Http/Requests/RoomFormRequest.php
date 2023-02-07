<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'capacity' => 'required|integer|min:1',
        ];
    }
}
