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
     * @param Property $property
     * @return JsonResponse
     */
    final public function getMatchingSearchProfiles(Property $property): JsonResponse
    {
        $propertySearchProfiles = $this->searchProfileRepository->getSearchProfilesByPropertyType($property->property_type_id);
        $propertyFields = (array) json_decode($property->fields);
        $result = [];

        if(!empty($propertySearchProfiles)){
            foreach($propertySearchProfiles as $profile){
                $searchFields = (array) json_decode($profile->search_fields);
                $score = 0;
                $missedMatching = 0;
                $matchedKeys = 0;
                $strictMatchesCount = 0;
                $looseMatchesCount = 0;
                $strictMatchesComparison = [];
                $looseMatchesComparison = [];
                $missedMatcheComparision = [];
                $missedMatcheComparision = [];

                foreach($propertyFields as $key => $value){
                    if(array_key_exists($key, $searchFields)){
                        $matchedKeys++;
                        $searchFieldValue =  $searchFields[$key];

                        //range values
                        if(is_array($searchFieldValue)){
                            if(count($searchFieldValue) == 2){
                                //convert to native types in case of strings
                                $min = $searchFieldValue[0];
                                $max = $searchFieldValue[1];
    
                                if($this->isInRange($min, $max, $value)){
                                    $score += 10;
                                    $strictMatchesCount++;
                                    //$strictMatchesComparison[] = ["min" => $min, "max" => $max, "value" => $value];
                                }else {
                                    if($this->isInRange($min, $max, $value, true)){
                                        $score += 10;
                                        $looseMatchesCount++;
                                        //$looseMatchesComparison[] = ["min" => $min, "max" => $max, "value" => $value];
                                    }else{
                                        $missedMatching++;
                                    }
                                }
                            }else {
                                $missedMatching++;
                            }
                           
                        }else {
                            if($searchFieldValue === $value){
                                $score += 10;
                                $strictMatchesCount++;
                            }else {
                                $missedMatching++;
                            }
                        }
                    }
                }

               
                if($missedMatching <= 0 && $score > 0){
                    $result[] = [
                        "searchProfileId" => $profile->id, 
                        "score" => $score, 
                        "strictMatchesCount" => $strictMatchesCount, 
                        "looseMatchesCount" => $looseMatchesCount,

                        //Debug Response
                        // "propertyFields" => $propertyFields,
                        // "searchFields" => $searchFields,
                        // "missedMatches" => $missedMatching,
                        // "missedMatchComparisons" => $missedMatcheComparision,
                        // "strictMatchComparisons" => $strictMatchesComparison,
                        // "looseMatchComparisons" => $looseMatchesComparison,
                    ];
                }

                

            }
        }
        

        //sort result 
        $scoreColumn = array_column($result, 'score');
        array_multisort($scoreColumn, SORT_DESC, $result);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

     /**
     * Check if value is in range
     *
     * @param mixed $min
     * @param mixed $max
     * @param mixed $value
     * @return bool
     */
    final private function isInRange($min, $max, $value, $applyDeviation = false): bool
    {
        $passedMin = false;
        $passedMax = false;
        $deviation = config("app.match_deviation");

        if(is_null($value) || !is_numeric($value)){
            //cant match values to null
            return false;
        }

        //convert to native type
        $value = $value * 1;

        if(is_null($min)){
            $passedMin = true;
        }else {
            if(is_numeric($min)){
                if($applyDeviation){
                    //apply deviation
                    $min = $min - (($deviation/100) * $min);
                }
    
               if(round($value, 6) >= round($min, 6)){
                    $passedMin = true;
                }
            }else {
                return false;
            }
        }

        if(is_null($max)){
            $passedMax = true;
        }else {
            if(is_numeric($max)){
                if($applyDeviation){
                    //apply deviation
                    $max = $max + (($deviation/100) * $max);
                 }

                if(round($value, 6) <= round($max, 6)){
                    $passedMax = true;
                }
            }else {
                return false;
            }
        }

        return ($passedMax && $passedMin);
    }
}
