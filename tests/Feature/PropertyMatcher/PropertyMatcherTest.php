<?php

namespace Tests\Feature\PropertyMatcher;

use App\Interfaces\TournamentService;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\SearchProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PropertyMatcherTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_valid_property_matches_valid_search_profiles(): void
    {
        $propertyType = PropertyType::factory()->create();

        $property = Property::factory([
            'property_type_id' => $propertyType->id,
            'fields' => json_encode([
                "area" => "180",
                "yearOfConstruction" => "2010",
                "rooms" => "5",
                "heatingType" => "gas",
                "parking" => true,
                "returnActual" => "12.8",
                "price" => "1500000"
            ]),
        ])->create();

        $searchProfile = SearchProfile::factory([
            'property_type_id' => $propertyType->id,
            "search_fields" => json_encode([
                "price" => ["0","2000000"],
                "area" => ["200",null],
                "yearOfConstruction" => ["2010",null],
                "rooms" => ["4",null],
                "returnActual" => ["15",null]
            ]),
        ])->create();

        $expectedData = [];
        //$expectedData[] = ["searchProfileId" => $searchProfile->id, "score" => 0, "strictMatchesCount" => 1,"looseMatchesCount" => 0];

        $response = $this->getJson('/api/match/'. $property->id);
        $response->dd();
        $response
            ->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }
}
