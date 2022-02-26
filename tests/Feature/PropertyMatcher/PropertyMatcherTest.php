<?php

namespace Tests\Feature\PropertyMatcher;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\SearchProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyMatcherTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_invalid_property_returns_not_found(): void
    {
        $response = $this->getJson('/api/match/'. 1);
        $response
            ->assertNotFound();
    }

    public function test_valid_property_returns_empty(): void
    {
        $propertyType = PropertyType::factory()->create();

        $property = Property::factory([
            "property_type_id" => $propertyType->id,
            "fields" => [
                "area" => "180",
                "yearOfConstruction" => "2010",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "price" => "1500000"
            ],
        ])->create();
        
        $response = $this->getJson('/api/match/'. $property->id);
        $response
            ->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure([
                'status',
                'data',
            ])
            ->assertJsonCount(0,'data');
    }

    public function test_valid_property_does_not_match_invalid_search_profiles(): void
    {
        $propertyType = PropertyType::factory()->create();

        $property = Property::factory([
            'property_type_id' => $propertyType->id,
            'fields' => [
                "area" => "180",
                "yearOfConstruction" => "2010",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "price" => "1500000"
            ],
        ])->create();

        $searchProfile = SearchProfile::factory([
            "property_type_id" => $propertyType->id,
            "search_fields" => [
                "price" => [null,"200"],
                "area" => [null,200],
                "rooms" => ["20",null],
            ],
        ])->create();

        $response = $this->getJson('/api/match/'. $property->id);
        $response
            ->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure([
                'status',
                'data',
            ])
            ->assertJsonCount(0,'data');
    }

    public function test_valid_property_matches_valid_search_profiles(): void
    {
        $propertyType = PropertyType::factory()->create();

        $property = Property::factory([
            "property_type_id" => $propertyType->id,
            "fields" => [
                "area" => "180",
                "yearOfConstruction" => "2010",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "lobby" => 20,
                "returnActual" => "12.8",
                "price" => "1500000"
            ],
        ])->create();

        $searchProfile = SearchProfile::factory([
            'property_type_id' => $propertyType->id,
            "search_fields" => [
                "price" => ["0","2000000"],
                "area" => ["200",null],
                "yearOfConstruction" => ["2010",null],
                "lobby" => '20',
                "rooms" => ["4",null],
                "returnActual" => ["15",null]
            ],
        ])->create();

        $response = $this->getJson('/api/match/'. $property->id);
        
        $response
            ->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure([
                'status',
                'data' =>[
                    '*' => [
                        "searchProfileId",
                        "score",
                        "strictMatchesCount",
                        "looseMatchesCount",
                    ]
                ] 
            ])
            ->assertJsonCount(1,'data');

        $serachProfileResult = $response['data'][0];

        $this->assertEquals($searchProfile->id,$serachProfileResult['searchProfileId'],);   
    }

    public function test_empty_property_does_not_match_empty_search_profiles(): void
    {
        $propertyType = PropertyType::factory()->create();

        $property = Property::factory([
            "property_type_id" => $propertyType->id,
            "fields" => [
            ],
        ])->create();

        $searchProfile = SearchProfile::factory([
            'property_type_id' => $propertyType->id,
            "search_fields" => [
               
            ],
        ])->create();

        $response = $this->getJson('/api/match/'. $property->id);
        
        $response
            ->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure([
                'status',
                'data' =>[
                    '*' => [
                        "searchProfileId",
                        "score",
                        "strictMatchesCount",
                        "looseMatchesCount",
                    ]
                ] 
            ])
            ->assertJsonCount(0,'data');  
    }

    public function test_valid_property_does_not_match_empty_search_profiles(): void
    {
        $propertyType = PropertyType::factory()->create();

        $property = Property::factory([
            "property_type_id" => $propertyType->id,
            "fields" => [
                "area" => "180",
            ],
        ])->create();

        $searchProfile = SearchProfile::factory([
            'property_type_id' => $propertyType->id,
            "search_fields" => [
               
            ],
        ])->create();

        $response = $this->getJson('/api/match/'. $property->id);
        
        $response
            ->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure([
                'status',
                'data' =>[
                    '*' => [
                        "searchProfileId",
                        "score",
                        "strictMatchesCount",
                        "looseMatchesCount",
                    ]
                ] 
            ])
            ->assertJsonCount(0,'data');  
    }
    
}
