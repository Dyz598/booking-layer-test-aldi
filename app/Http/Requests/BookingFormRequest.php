<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'room_id'   => 'required|integer',
            'starts_at' => 'required|date_format:Y-m-d',
            'ends_at'   => 'required|date_format:Y-m-d|after:starts_at',
        ];
    }
}
