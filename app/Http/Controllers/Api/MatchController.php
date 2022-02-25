<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\Contracts\PropertyRepositoryContract;
use App\Repositories\Contracts\SearchProfileRepositoryContract;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class MatchController extends Controller
{
    use ApiResponse;

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
     * @param Property $property
     * @return JsonResponse
     */
    final public function getMatchingSearchProfiles(Property $property): JsonResponse
    {
        $propertySearchProfiles = $this->searchProfileRepository->getSearchProfilesByPropertyType($property->property_type_id);
        $propertyFields = $property->fields;
        $result = [];

        if(!empty($propertySearchProfiles)){
            foreach($propertySearchProfiles as $profile){
                $searchFields = $profile->search_fields;

                //result data
                $score = 0;
                $missedMatchesCount = 0;
                $strictMatchesCount = 0;
                $looseMatchesCount = 0;

                foreach($propertyFields as $key => $value){
                    //property field is in search field
                    if(array_key_exists($key, $searchFields)){
                        $searchFieldValue =  $searchFields[$key];

                        $data = $this->matchFields($searchFieldValue, $value);

                        $score += $data["score"];
                        $strictMatchesCount += $data["strictMatchesCount"];
                        $looseMatchesCount += $data["looseMatchesCount"];
                        $missedMatchesCount += $data["missedMatchesCount"];
                    }
                }
                
                //add only matching results with no miss matches
                if($missedMatchesCount <= 0 && $score > 0){
                    $result[] = [
                        "searchProfileId" => $profile->id, 
                        "score" => $score, 
                        "strictMatchesCount" => $strictMatchesCount, 
                        "looseMatchesCount" => $looseMatchesCount,
                    ];
                }
            }
        }
        
        //sort result 
        $scoreColumn = array_column($result, 'score');
        array_multisort($scoreColumn, SORT_DESC, $result);

        return $this->sendResponse($result, 'Property matches retrieved successfully');
    }
    
    /**
     * Match field values
     *
     * @param mixed $searchFieldValue
     * @param mixed $value
     * @return array
     */
    private function matchFields($searchFieldValue, $value): array
    {
        //return format
        $data = [
            "score" => 0, 
            "strictMatchesCount" => 0,
            "looseMatchesCount" => 0,
            "missedMatchesCount" => 0,
        ];

        //range values match check
        if(is_array($searchFieldValue) && count($searchFieldValue) == 2){
           
            $min = $searchFieldValue[0];
            $max = $searchFieldValue[1];
            
            //strict match check
            if($this->isInRange($min, $max, $value)){
                $data["score"] = 10;
                $data["strictMatchesCount"] = 1;
                return $data;
            }

            //loose match check
            if($this->isInRange($min, $max, $value, true)){
                $data["score"] = 10;
                $data["looseMatchesCount"] = 1;
                return $data;
            }
        
        }

        //direct type match check
        if($searchFieldValue === $value){
            $data["score"] = 10;
            $data["strictMatchesCount"] = 1;
            return $data;
        }

         //missed match
         $data["missedMatchesCount"] = 1;
         return $data;
    }

     /**
     * Check if value is in range
     *
     * @param mixed $min
     * @param mixed $max
     * @param mixed $value
     * @param bool $applyDeviation
     * @return bool
     */
     private function isInRange($min, $max, $value, $applyDeviation = false): bool
    {
        $passedMin = false;
        $passedMax = false;
        $deviation = config("app.match_deviation");

        if(is_null($value)) {
            //null value matches anything
            return true;
        }

        //non numeric values cannont be compared
        if(!is_numeric($value)){
            return false;
        }

        //convert to native type
        $value = $value * 1;

        //Check Min Value
        if(is_null($min)){
            $passedMin = true;
        }else {
           
            if($applyDeviation){
                $min = $min - (($deviation/100) * $min);
            }

            if(round($value, 6) >= round($min, 6)){
                $passedMin = true;
            }
        }

        //Check Max Value
        if(is_null($max)){
            $passedMax = true;
        }else {
          
            if($applyDeviation){
                //apply deviation
                $max = $max + (($deviation/100) * $max);
            }

            if(round($value, 6) <= round($max, 6)){
                $passedMax = true;
            }
        
        }

        return ($passedMax && $passedMin);
    }
}
