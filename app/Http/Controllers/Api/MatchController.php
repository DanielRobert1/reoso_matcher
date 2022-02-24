<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryContract;
use App\Repositories\Contracts\SearchProfileRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    /**
     * @var PropertyRepositoryContract
     */
    private $propertyRepository;
    
    /**
     * @var SearchProfileRepositoryContract
     */
    private $searchProfileRepository;

    public function __construct(PropertyRepositoryContract $propertyRepository, SearchProfileRepositoryContract $searchProfileRepository)
    {
        $this->propertyRepository = $propertyRepository;
        $this->searchProfileRepository = $searchProfileRepository;
    }


    /**
     * Property Matches
     *
     * This returns all search profiles that match the specified property
     *
     *
     * @param Property $property
     * @return JsonResponse
     */
    final public function getMatchingSearchProfiles(Property $property): JsonResponse
    {
        $propertyFields = json_decode($property->fields);

        $propertySearchProfiles = $property->searchProfiles;

        if(empty($propertySearchProfiles)){

        }
        

        return response()->json([
            'status' => 'success',
            'data' => $propertyFields,
            'message' => "Read notifications deleted!"
        ]);
    }
}
