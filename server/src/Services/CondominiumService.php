<?php

namespace App\Services;

use App\Http\Error\HttpBadRequestException;
use App\Http\Error\HttpNotFoundException;
use App\Http\Error\HttpUnprocessableEntityException;
use App\Models\Condominium;

class CondominiumService
{
    public function list()
    {
        return Condominium::all();
    }

    public function find(int $id): Condominium
    {
        $condominium = Condominium::find($id);

        if (!$condominium) {
            throw new HttpNotFoundException('Condominium not found');
        }

        return $condominium;
    }

    public function create(array $data): Condominium
    {
        // Capture the sanitized and validated data
        $data = $this->validateCondominiumData($data);

        $condominium = Condominium::create([
            'name' => $data['name'],
            'zip_code' => $data['zip_code'],
            'url' => $data['url']
        ]);

        return $condominium;
    }

    public function update(int $id, array $data): Condominium
    {
        $condominium = $this->find($id);

        // Capture the sanitized and validated data
        $data = $this->validateCondominiumData($data);

        $condominium->fill([
            'name' => $data['name'],
            'zip_code' => $data['zip_code'],
            'url' => $data['url']
        ]);

        $condominium->save();

        return $condominium;
    }

    public function delete(int $id): bool
    {
        $condominium = $this->find($id);

        // Check if condominium has units
        if ($condominium->units()->count() > 0) {
            throw new HttpBadRequestException('Cannot delete condominium with units');
        }

        $deleted = $condominium->delete();

        return $deleted;
    }

    /** @throws HttpUnprocessableEntityException */
    private function validateCondominiumData(array $data): array
    {
        if (empty($data['name'])) {
            throw new HttpUnprocessableEntityException('Name is required');
        }

        if (empty($data['zip_code'])) {
            throw new HttpUnprocessableEntityException('Zip code is required');
        }

        // Trim URL input to remove unnecessary whitespace
        $data['url'] = trim($data['url'] ?? '');

        // If URL is empty after trimming, treat it as null
        if ($data['url'] === '') {
            $data['url'] = null;
        // Validate the URL format only if it's not null
        } elseif (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            throw new HttpUnprocessableEntityException('Invalid URL. If you do not have one, you may leave it empty.');
        }

        // Validate ZIP code format
        if (!preg_match('/^\d{8}$/', $data['zip_code'])) {
            throw new HttpUnprocessableEntityException('Invalid ZIP code format');
        }

        // Return the sanitized and validated data array
        return $data;
    }
}
