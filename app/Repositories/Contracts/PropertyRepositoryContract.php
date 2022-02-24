<?php


namespace App\Repositories\Contracts;


use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

interface PropertyRepositoryContract
{
    /**
     * @return Property[]|Collection
     */
    public function getAllProperties(): Collection;

    /**
     * @param int $property_id
     * @return Property
     */
    public function getById(int $property_id): Property;

    /**
     * @param array $property_ids
     * @param array $with
     * @return Collection|Property[]
     */
    public function getPropertiesByIds(array $property_ids, array $with = []): Collection;

    /**
     * @param Property $property
     * @param array $data
     * @return bool
     */
    public function updateProperty(Property $property, array $data): bool;
}
