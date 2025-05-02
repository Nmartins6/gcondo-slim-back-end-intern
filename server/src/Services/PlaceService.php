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
        $this->validate($data);
        return Place::create($data);
    }

    public function update(int $id, array $data): Place
    {
        $place = Place::findOrFail($id);

        $this->validate($data);
        $place->update($data);

        return $place;
    }

    public function delete(int $id): bool
    {
        $place = Place::findOrFail($id);
        return $place->delete();
    }

    private function validate(array $data): void
    {
        if (empty($data['name'])) {
            throw new HttpUnprocessableEntityException('Name is required');
        }

        if (empty($data['max_people']) || !is_numeric($data['max_people'])) {
            throw new HttpUnprocessableEntityException('max_people must be a number');
        }

        if (isset($data['square_meters']) && !is_numeric($data['square_meters'])) {
            throw new HttpUnprocessableEntityException('square_meters must be a number');
        }
    }
}
