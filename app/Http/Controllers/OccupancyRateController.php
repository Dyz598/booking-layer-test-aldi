<?php

namespace App\Http\Controllers;

use Acme\OccupancyRate\Contract\CalculateOccupancyRate;
use Acme\OccupancyRate\Repository\DailyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Repository\MonthlyRoomOccupancyRateRepository;
use Acme\OccupancyRate\Service\OccupancyRateCalculator;
use Acme\Room\Repository\RoomRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class OccupancyRateController extends Controller
{
    public function __construct(
        protected DailyRoomOccupancyRateRepository   $dailyRepository,
        protected MonthlyRoomOccupancyRateRepository $monthlyRepository,
        protected RoomRepository                     $roomRepository,
        protected OccupancyRateCalculator            $rateCalculator,
    ) {}

    public function daily(Request $request): JsonResponse
    {
        $payload = ['date' => $request->route('date'), 'room_ids' => $request->query('room_ids')];

        Validator::make($payload, [
            'date'       => 'required|date_format:Y-m-d',
            'room_ids'   => 'nullable|array',
            'room_ids.*' => 'integer',
        ])->validate();

        $date = Carbon::createFromFormat('Y-m-d', $payload['date']);
        $rooms = $payload['room_ids'];

        $cacheTag = sprintf('daily_occupancy_rates_%s', $date->format('Y-m-d'));
        $cacheKey = sprintf('%s_%s', $cacheTag, base64_encode(json_encode($rooms)));

        return Cache::tags($cacheTag)->remember($cacheKey, 60 * 60 * 24, function () use ($date, $rooms) {
            $data = $this->dailyRepository->sumByDateAndRoomIds($date, $rooms);
            $totalCapacity = $this->roomRepository->sumCapacity($rooms);

            return response()->json([
                'occupancy_rate' => $this->rateCalculator->calculate(new CalculateOccupancyRate(
                    totalBooked  : $data->total_booked ?? 0,
                    totalCapacity: $totalCapacity,
                    totalBlocked : $data->total_blocked ?? 0,
                )),
            ]);
        });
    }

    public function monthly(Request $request): JsonResponse
    {
        $payload = ['month_year' => $request->route('month_year'), 'room_ids' => $request->query('room_ids')];

        Validator::make($payload, [
            'month_year' => 'required|date_format:Y-m',
            'room_ids'   => 'nullable|array',
            'room_ids.*' => 'integer',
        ])->validate();

        $monthYear = explode('-', $payload['month_year']);
        $date = now()->startOfMonth()->setYear((int) $monthYear[0])->setMonth((int) $monthYear[1]);
        $rooms = $payload['room_ids'];

        $cacheTag = sprintf('monthly_occupancy_rates_%s', $date->format('Y-m-d'));
        $cacheKey = sprintf('%s_%s', $cacheTag, base64_encode(json_encode($rooms)));

        return Cache::tags($cacheTag)->remember($cacheKey, 60 * 60 * 24, function () use ($date, $rooms) {
            $data = $this->monthlyRepository->sumByMonthYearAndRoomIds($date->month, $date->year, $rooms);
            $totalCapacity = $this->roomRepository->sumCapacity($rooms);

            return response()->json([
                'occupancy_rate' => $this->rateCalculator->calculate(new CalculateOccupancyRate(
                    totalBooked  : $data->total_booked ?? 0,
                    totalCapacity: $totalCapacity * $date->daysInMonth,
                    totalBlocked : $data->total_blocked ?? 0,
                )),
            ]);
        });
    }
}
