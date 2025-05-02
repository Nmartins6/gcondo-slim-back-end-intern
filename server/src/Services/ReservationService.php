<?php

namespace App\Services;

use App\Http\Error\HttpUnprocessableEntityException;
use App\Models\Reservation;
use App\Models\Unit;
use App\Models\Place;

class ReservationService
{
    public function create(array $data): Reservation
    {
        $this->validateReservationData($data);
        $this->checkForConflicts($data);

        return Reservation::create($data);
    }

    public function list(): array
    {
        return Reservation::with(['unit', 'place'])->get()->toArray();
    }


    public function update(int $id, array $data): Reservation
    {
        $reservation = Reservation::findOrFail($id);

        $this->validateReservationData($data);
        $this->checkForConflicts($data);

        $reservation->update($data);

        return $reservation;
    }

    public function find(int $id): array
    {
        $reservation = Reservation::with(['unit', 'place'])->findOrFail($id);
        return $reservation->toArray();
    }

    public function delete(int $id): bool
    {
        $reservation = Reservation::findOrFail($id);
        return $reservation->delete();
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

    private function checkForConflicts(array $data): void
    {
        if (empty($data['place_id'])) {
            return;
        }

        $exists = Reservation::where('place_id', $data['place_id'])
            ->where('date', $data['date'])
            ->exists();

        if ($exists) {
            throw new HttpUnprocessableEntityException('A reservation already exists for this place and date.');
        }
    }
}
