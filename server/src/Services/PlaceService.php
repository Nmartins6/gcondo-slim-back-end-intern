<?php

namespace App\Services;

use App\Http\Error\HttpNotFoundException;
use App\Http\Error\HttpUnprocessableEntityException;
use App\Models\Place;

class PlaceService
{
    public function list(): array
    {
        return Place::all()->toArray();
    }

    public function find(int $id): Place
    {
        $place = Place::find($id);

        if (!$place) {
            throw new HttpNotFoundException("Place not found with id $id");
        }

        return $place;
    }

    public function create(array $data): Place
    {
        $this->validatePlaceData($data);

        $place = Place::create([
            'name' => $data['name'],
            'max_people' => $data['max_people'],
            'square_meters' => $data['square_meters'] ?? null,
        ]);

        return $place;
    }

    public function update(int $id, array $data): Place
    {
        $place = $this->find($id);

        $this->validatePlaceData($data);

        $place->fill([
            'name' => $data['name'],
            'max_people' => $data['max_people'],
            'square_meters' => $data['square_meters'] ?? null,
        ]);

        $place->save();

        return $place;
    }

    public function delete(int $id): bool
    {
        $place = $this->find($id);

        return $place->delete();
    }

    /** @throws HttpUnprocessableEntityException */
    private function validatePlaceData(array $data): void
    {
        if (empty($data['name'])) {
            throw new HttpUnprocessableEntityException('Name is required');
        }

        if (!isset($data['max_people']) || !is_numeric($data['max_people']) || $data['max_people'] <= 0) {
            throw new HttpUnprocessableEntityException('max_people must be a positive number');
        }

        if (isset($data['square_meters']) && !is_null($data['square_meters'])) {
            if (!is_numeric($data['square_meters']) || $data['square_meters'] <= 0) {
                throw new HttpUnprocessableEntityException('square_meters must be a positive number');
            }
        }
    }
}
