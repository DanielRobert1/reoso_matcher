<?php


namespace App\Repositories\Contracts;


use App\Models\SearchProfile;
use Illuminate\Database\Eloquent\Collection;

interface SearchProfileRepositoryContract
{
    /**
     * @return SearchProfile[]|Collection
     */
    public function getAllSearchProfiles(): Collection;

    /**
     * @param int $search_profile_id
     * @return SearchProfile
     */
    public function getById(int $search_profile_id): SearchProfile;

    /**
     * @param array $search_profile_ids
     * @param array $with
     * @return Collection|SearchProfile[]
     */
    public function getSearchProfilesByIds(array $search_profile_ids, array $with = []): Collection;

    /**
     * @param SearchProfile $search_profile
     * @param array $data
     * @return bool
     */
    public function updateSearchProfile(SearchProfile $search_profile, array $data): bool;

}
