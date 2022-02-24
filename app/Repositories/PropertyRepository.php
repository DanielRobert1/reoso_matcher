<?php


namespace App\Repositories;


use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Recca0120\Repository\EloquentRepository;

class PropertyRepository extends EloquentRepository implements PropertyRepositoryContract
{
    public function __construct(Property $property)
    {
        parent::__construct($property);
    }

    /**
     * @return Property[]|Collection
     */
    final public function getAllProperties(): Collection
    {
        return $this->newQuery()->get();
    }

    /**
     * @param int $property_id
     * @return Property
     */
    final public function getById(int $property_id): Property
    {
        return $this->find($property_id);
    }

    /**
     * @param array $property_ids
     * @param array $with
     * @return Collection|Property[]
     */
    final public function getPropertiesByIds(array $property_ids, array $with = []): Collection
    {
        return $this->newQuery()->whereIn('id', $property_ids)->with($with)->get();
    }

    /**
     * @param Property $property
     * @param array $data
     * @return bool
     */
    final public function updateProperty(Property $property, array $data): bool
    {
        return $property->update($data);
    }
}
