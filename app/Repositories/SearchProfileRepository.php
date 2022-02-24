<?php


namespace App\Repositories;


use App\Models\SearchProfile;
use App\Repositories\Contracts\SearchProfileRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Recca0120\Repository\EloquentRepository;

class SearchProfileRepository extends EloquentRepository implements SearchProfileRepositoryContract
{
    public function __construct(SearchProfile $search_profile)
    {
        parent::__construct($search_profile);
    }

    /**
     * @return SearchProfile[]|Collection
     */
    final public function getAllSearchProfiles(): Collection
    {
        return $this->newQuery()->get();
    }

    /**
     * @param int $search_profile_id
     * @return SearchProfile
     */
    final public function getById(int $search_profile_id): SearchProfile
    {
        return $this->find($search_profile_id);
    }

    /**
     * @param array $search_profile_ids
     * @param array $with
     * @return Collection|SearchProfile[]
     */
    final public function getSearchProfilesByIds(array $search_profile_ids, array $with = []): Collection
    {
        return $this->newQuery()->whereIn('id', $search_profile_ids)->with($with)->get();
    }

    /**
     * @param SearchProfile $search_profile
     * @param array $data
     * @return bool
     */
    final public function updateSearchProfile(SearchProfile $search_profile, array $data): bool
    {
        return $search_profile->update($data);
    }
}
