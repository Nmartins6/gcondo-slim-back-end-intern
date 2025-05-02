<?php

namespace App\Services;

use App\Http\Error\HttpUnprocessableEntityException;
use App\Models\Reservation;
use App\Models\Unit;
use App\Models\Place;

class ReservationService
{
    /**
     * Create a reservation with basic validation.
     * 
     * @throws HttpUnprocessableEntityException
     */
    public function create(array $data): Reservation
    {
        $this->validateReservationData($data);

        return Reservation::create($data);
    }

    public function list(): array
    {
        return Reservation::with(['unit', 'place'])->get()->toArray();
    }

    private function validateReservationData(array $data): void
    {
        if (empty($data['name'])) {
            throw new HttpUnprocessableEntityException('Name is required');
        }

        if (empty($data['unit_id']) || !Unit::find($data['unit_id'])) {
            throw new HttpUnprocessableEntityException('Valid unit_id is required');
        }

        if (!empty($data['place_id']) && !Place::find($data['place_id'])) {
            throw new HttpUnprocessableEntityException('Invalid place_id');
        }

        if (empty($data['people_amount']) || !is_numeric($data['people_amount'])) {
            throw new HttpUnprocessableEntityException('People amount must be a number');
        }

        if (empty($data['date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date'])) {
            throw new HttpUnprocessableEntityException('Invalid date format (expected YYYY-MM-DD)');
        }
    }
}
